<?php

class RouterConfig {

	private static $route = array();

	private const METHOD = 'method';
	private const OBJECT = 'object';

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
		$route = $route[$_SERVER['REQUEST_METHOD']];

		if (!isset($route)) {
			RouterConfig::submitError(ErrorsPaths::methodNotAllowed);
			return;
		}

		$route[RouterConfig::METHOD]->invoke(
			$route[RouterConfig::OBJECT]
		);
	}

	public static function submitError($uri = 'errors/BadRequest') {
		ViewLoader::instance()->load($uri);
	}

}