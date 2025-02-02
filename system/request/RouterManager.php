<?php

namespace System\Core;

use Configs\ErrorsPaths;
use System\Annotations\Route\Routable;
use System\Annotations\Route\Route;
use System\Core\Instancer;
use System\Core\ViewLoader;
use System\Interfaces\Request\Chain;
use System\Logger;

use Exception;
use ReflectionClass;
use ReflectionMethod;

class RouterManager extends Chain {

	private array $visitedClass = [];
	private static $route = array();

	private const METHOD = 'method';
	private const OBJECT = 'object';

	private const URI = 'uri';
	private const TYPES = 'types';

	public function __construct() {}

	#[\Override]
	public function configure() : bool {
		foreach (get_declared_classes() as $className) {
			if (isset($this->visitedClass[$className]) && $this->visitedClass[$className])
				continue;

			$this->visitedClass[$className] = true;

			$class = new ReflectionClass($className);
			$attributes = $class->getAttributes(Routable::class);
			
			if (empty($attributes)) {
				continue;
			}

			$object = Instancer::get($class);

			$routable = Instancer::getByReflectionAttribute($attributes[0]);
			$classUri = preg_replace("/\/$/", '', preg_replace("/^[^\/]/", '/', $routable->getName()));

			foreach ($class->getMethods() as $method) {
				$attributes = $method->getAttributes(Route::class);

				if(empty($attributes) || $method->isConstructor() || $method->isDestructor())
					continue;

				$route = Instancer::getByReflectionAttribute($attributes[0]);

				$methodUri = preg_replace("/\/$]/", '', preg_replace("/^[^\/]/", '/', $route->getValue()));
				$realPath = $classUri.$methodUri;

				$arguments = [
					RouterManager::URI 	=> $realPath,
					RouterManager::TYPES=> $route->getMethods(),
				];

				RouterManager::add($arguments, $object, $method);
			}
		}

		return true;
	}

	public static function add(array $arguments, object $object, ReflectionMethod $method) {
		if (!isset($arguments[RouterManager::URI]) || !isset($arguments[RouterManager::TYPES])) {
			throw new Exception("Invalid Params");
		}

		$uri = $arguments[RouterManager::URI];
		$types = $arguments[RouterManager::TYPES];

		if (is_string($types)) {
			$types = [$types];
		}

		foreach ($types as $type) {
 			if (isset(RouterManager::$route[$uri][$type])) {
				throw new Exception("URL is defined more than one way");
			}

			RouterManager::$route[$uri][$type] = [
				RouterManager::OBJECT => $object,
				RouterManager::METHOD => $method,
			];
		}
	}

	#[\Override]
	public function execute(string $url, array $params = []) : mixed {

		$views = preg_replace("/\//", "\/", VIEWS_PATH);
		$views = preg_replace("/\./", "", $views);
		if (preg_match("/" . $views . ".*/", $url)) {
			$uri = preg_replace("/" . $views . "/", "", $url);
			RouterManager::submitView($uri);
			return null;
		}

		if (!isset(RouterManager::$route[$url])) {
			if ($url == "/") {
				RouterManager::submitView("index");
				return null;
			}

			RouterManager::submitView(ErrorsPaths::notFound);
			return null;
		}

		$route = RouterManager::$route[$url];
		$method = $_SERVER['REQUEST_METHOD'];

		if (!isset($route[$method])) {
			RouterManager::submitView(ErrorsPaths::methodNotAllowed);
			return null;
		}

		try {
			return $route[$method][RouterManager::METHOD]->invokeArgs(
				$route[$method][RouterManager::OBJECT],
				$params
			);
		} catch (\Throwable $th) {
			(new Logger(new RouterManager()))->log($th);

			RouterManager::submitView(ErrorsPaths::badRequest, $th->getMessage());
			return null;
		}
	}

	private static function submitView($uri = ErrorsPaths::internalServerError, $error = NULL) {
		ViewLoader::instance()->load($uri, array('error' => $error));
	}
}