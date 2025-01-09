<?php

class RouterConfig {

	private static $route = array();

	private const METHOD = 'method';
	private const OBJECT = 'object';

	public static function configure() {
		foreach (get_declared_classes() as $className) {
			$class = new ReflectionClass($className);
			$attributes = $class->getAttributes(Routable::class);
			
			if (empty($attributes)) {
				continue;
			}

			$object = $class->newInstance();

			$classUri = $attributes[0]->getArguments()[0];
			$classUri = preg_replace("/^[^\/]/", '/', $classUri);
			$classUri = preg_replace("/\/$/", '', $classUri);

			foreach ($class->getMethods() as $method) {
				$attributes = $method->getAttributes(Route::class);

				if(empty($attributes) || $method->isConstructor() || $method->isDestructor())
					continue;

				$arguments = $attributes[0]->getArguments();

				$methodUri = $arguments[0];
				$methodUri = preg_replace("/^[^\/]/", '/', $methodUri);
				$methodUri = preg_replace("/\/$]/", '', $methodUri);

				$realPath = $classUri.$methodUri;
				RouterConfig::add($realPath, $arguments[1], $object, $method);
			}
		}
	}

	public static function add(string $uri, string $type, object $object, ReflectionMethod $method) {
		if(isset(RouterConfig::$route[$uri]) && isset(RouterConfig::$route[$uri][$type])) {
			throw new Exception("URL is defined more than one way");
		}

		RouterConfig::$route[$uri][$type] = [
			RouterConfig::OBJECT => $object,
			RouterConfig::METHOD => $method
		];
	}

	public static function submit($uri) {
		preg_match_all("/\/\w*/", $uri, $occurences);
		foreach ($occurences as $occu) {
			foreach ($occu as $oc) {
				$oc = '/'.preg_replace('/\//', '\/', $oc).'/';
				if (preg_match($oc, __DIR__)) {
					$uri = preg_replace($oc, "", $uri);
				}
			}
		} 

		if (preg_match("/^(errors)\/.*/", $uri)) {
			RouterConfig::submitError($uri);
			return;
		}

		if (!isset(RouterConfig::$route[$uri])) {
			RouterConfig::submitError(ErrorsPaths::notFound);
			return;
		}

		$route = RouterConfig::$route[$uri];
		$method = $_SERVER['REQUEST_METHOD'];

		if (!isset($route[$method])) {
			RouterConfig::submitError(ErrorsPaths::methodNotAllowed);
			return;
		}

		$route[$method][RouterConfig::METHOD]->invoke(
			$route[RouterConfig::OBJECT]
		);
	}

	public static function submitError($uri = 'errors/BadRequest') {
		ViewLoader::instance()->load($uri);
	}

}