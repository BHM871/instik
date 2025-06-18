<?php

namespace System\Request;

use Configs\ErrorsPaths;
use System\Annotations\Route\Routable;
use System\Annotations\Route\Route;
use System\Core\Instancer;
use System\Core\ViewLoader;
use System\Interfaces\Request\Layer;
use System\Logger;

use Exception;
use ReflectionClass;
use ReflectionMethod;

class RouterLayer extends Layer {

	private static $route = array();

	private const METHOD = 'method';
	private const OBJECT = 'object';

	private const URI = 'uri';
	private const TYPES = 'types';

	public function __construct() {}

	#[\Override]
	public function setupToClassName(string $className) {
		$class = new ReflectionClass($className);
		$attributes = $class->getAttributes(Routable::class);
		
		if (empty($attributes))
			return;

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
				RouterLayer::URI 	=> $realPath,
				RouterLayer::TYPES=> $route->getMethods(),
			];

			RouterLayer::add($arguments, $object, $method);
		}
	}

	public static function add(array $arguments, object $object, ReflectionMethod $method) {
		if (!isset($arguments[RouterLayer::URI]) || !isset($arguments[RouterLayer::TYPES])) {
			throw new Exception("Invalid Params");
		}

		$uri = $arguments[RouterLayer::URI];
		$types = $arguments[RouterLayer::TYPES];

		if (is_string($types)) {
			$types = [$types];
		}

		foreach ($types as $type) {
 			if (isset(RouterLayer::$route[$uri][$type])) {
				throw new Exception("URL is defined more than one way");
			}

			RouterLayer::$route[$uri][$type] = [
				RouterLayer::OBJECT => $object,
				RouterLayer::METHOD => $method,
			];
		}
	}

	#[\Override]
	public function execute(string $url, array $params = []) : mixed {

		$views = preg_replace("/\//", "\/", VIEWS_PATH);
		$views = preg_replace("/\./", "", $views);
		if (preg_match("/" . $views . ".*/", $url)) {
			$uri = preg_replace("/" . $views . "/", "", $url);
			RouterLayer::submitView($uri);
			return null;
		}

		if (!isset(RouterLayer::$route[$url])) {
			if ($url == "/") {
				RouterLayer::submitView("index");
				return null;
			}

			RouterLayer::submitView(ErrorsPaths::notFound);
			return null;
		}

		$route = RouterLayer::$route[$url];
		$method = $_SERVER['REQUEST_METHOD'];

		if (!isset($route[$method])) {
			RouterLayer::submitView(ErrorsPaths::methodNotAllowed);
			return null;
		}

		try {
			return $route[$method][RouterLayer::METHOD]->invokeArgs(
				$route[$method][RouterLayer::OBJECT],
				$params
			);
		} catch (\Throwable $th) {
			(new Logger(new RouterLayer()))->log($th);

			RouterLayer::submitView(ErrorsPaths::badRequest, $th->getMessage());
			return null;
		}
	}

	private static function submitView($uri = ErrorsPaths::internalServerError, $error = NULL) {
		ViewLoader::instance()->load($uri, array('error' => $error));
	}
}