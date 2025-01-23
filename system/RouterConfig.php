<?php

namespace System;

use Configs\ErrorsPaths;
use System\Annotations\Routable;
use System\Annotations\Route;
use System\Core\Instancer;
use System\Core\ViewLoader;
use System\Security\SessionManager;

use Exception;
use ReflectionClass;
use ReflectionMethod;

class RouterConfig {

	private static $route = array();

	private const METHOD = 'method';
	private const OBJECT = 'object';
	private const SECURITY = 'security';

	private const URI = 'uri';
	private const TYPES = 'types';

	private function __construct() {}

	public static function configure() {

		foreach (get_declared_classes() as $className) {
			$class = new ReflectionClass($className);
			$attributes = $class->getAttributes(Routable::class);
			
			if (empty($attributes)) {
				continue;
			}

			$object = Instancer::get($class);

			$routable = (new ReflectionClass(Routable::class))->newInstanceArgs($attributes[0]->getArguments());
			$classUri = preg_replace("/\/$/", '', preg_replace("/^[^\/]/", '/', $routable->name));

			foreach ($class->getMethods() as $method) {
				$attributes = $method->getAttributes(Route::class);

				if(empty($attributes) || $method->isConstructor() || $method->isDestructor())
					continue;

				$route = (new ReflectionClass(Route::class))->newInstanceArgs($attributes[0]->getArguments());				

				$methodUri = preg_replace("/\/$]/", '', preg_replace("/^[^\/]/", '/', $route->getValue()));
				$realPath = $classUri.$methodUri;

				$arguments = [
					RouterConfig::URI 		=> $realPath,
					RouterConfig::TYPES 	=> $route->getMethods(),
					RouterConfig::SECURITY	=> $route->getNeedAuthentication()
				];
				
				RouterConfig::add($arguments, $object, $method);
			}
		}
	}

	public static function add(array $arguments, object $object, ReflectionMethod $method) {
		if (!isset($arguments[RouterConfig::URI]) || !isset($arguments[RouterConfig::TYPES]) || !isset($arguments[RouterConfig::SECURITY])) {
			throw new Exception("Invalid Params");
		}

		$uri = $arguments[RouterConfig::URI];
		$types = $arguments[RouterConfig::TYPES];
		$needAuthentication = $arguments[RouterConfig::SECURITY];

		if (is_string($types)) {
			$types = [$types];
		}

		foreach ($types as $type) {
 			if (isset(RouterConfig::$route[$uri][$type])) {
				throw new Exception("URL is defined more than one way");
			}

			RouterConfig::$route[$uri][$type] = [
				RouterConfig::OBJECT => $object,
				RouterConfig::METHOD => $method,
				RouterConfig::SECURITY => $needAuthentication
			];
		}
	}

	public static function submit($uri) {
		preg_match_all("/\/\w+/", $uri, $occurences);
		foreach ($occurences as $occu) {
			foreach ($occu as $oc) {
				$oc = '/'.preg_replace('/\//', '\/', $oc).'/';
				if (preg_match($oc, __DIR__)) {
					$uri = preg_replace($oc, "", $uri);
				}
			}
		}

		$views = preg_replace("/\//", "\/", VIEWS_PATH);
		$views = preg_replace("/\./", "", $views);
		if (preg_match("/" . $views . ".*/", $uri)) {
			$uri = preg_replace("/" . $views . "/", "", $uri);
			RouterConfig::submitView($uri);
			return;
		}

		if (!isset(RouterConfig::$route[$uri])) {
			if ($uri == "/") {
				RouterConfig::submitView("index");
				return;
			}

			RouterConfig::submitView(ErrorsPaths::notFound);
			return;
		}

		$route = RouterConfig::$route[$uri];
		$method = $_SERVER['REQUEST_METHOD'];

		if (!isset($route[$method])) {
			RouterConfig::submitView(ErrorsPaths::methodNotAllowed);
			return;
		}

		if ($route[$method][RouterConfig::SECURITY]) {
			if (!Instancer::get(SessionManager::class)->isAuthenticated()) {	
				RouterConfig::submitView(ErrorsPaths::forbbiden);
				return;
			}
		}

		try {
			$route[$method][RouterConfig::METHOD]->invoke(
				$route[$method][RouterConfig::OBJECT]
			);
		} catch (\Throwable $th) {
			(new Logger(new RouterConfig()))->log($th);

			RouterConfig::submitView(ErrorsPaths::badRequest, $th->getMessage());
		}
	}

	private static function submitView($uri = ErrorsPaths::internalServerError, $error = NULL) {
		ViewLoader::instance()->load($uri, array('error' => $error));
	}

}