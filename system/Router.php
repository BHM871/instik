<?php

class Router {

	private static $route = array();
	private static $objects = array();

	public static function add(string $uri, object $object, ReflectionMethod $method) {
		if(isset(Router::$route[$uri])) {
			throw new Exception("URL is defined more than one way");
		}

		Router::$objects[$uri] = $object;
		Router::$route[$uri] = $method;
	}

	public static function submit($uri) {
		Router::$route[$uri]->invoke(
			Router::$objects[$uri]
		);
	}

}