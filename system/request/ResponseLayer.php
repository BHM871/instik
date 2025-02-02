<?php

namespace System\Request;

use System\Annotations\Request\ResponseBody;
use System\Annotations\Route\Routable;
use System\Annotations\Route\Route;
use System\Core\ViewLoader;
use System\Interfaces\Request\Layer;
use System\Security\SecurityLayer;

use ReflectionClass;

class ResponseLayer extends Layer {

	private array $visitedClass = [];
	private array $routes = [];
	private ViewLoader $loader;

	public function __construct(
		SecurityLayer $next
	) {
		$this->loader = ViewLoader::instance();
		parent::__construct($next);
	}

	#[\Override]
	public function configure() : bool {
		foreach (get_declared_classes() as $className) {
			if (isset($this->visitedClass[$className]) && $this->visitedClass[$className])
				continue;

			$this->visitedClass[$className] = true;

			$reflection = new ReflectionClass($className);
			$attributes = $reflection->getAttributes(Routable::class);

			if (empty($attributes))
				continue;

			$uri = $attributes[0]->getArguments()[0];
			$uri = preg_replace("/^[^\/]/", "/", $uri);

			foreach ($reflection->getMethods() as $method) {
				$attributes = $method->getAttributes(ResponseBody::class);

				if (empty($attributes))
					continue;

				$attributes = $method->getAttributes(Route::class);

				if (empty($attributes))
					continue;

				$route = (new ReflectionClass(Route::class))->newInstanceArgs($attributes[0]->getArguments());

				$uri .= preg_replace("/^[^\/]/", "/", $route->getValue());

				if (is_string($route->getMethods())) {
					$this->addRoute($route->getMethods(), $uri);
				} else if (is_array($route->getMethods())) {
					foreach ($route->getMethods() as $met)
						$this->addRoute($met, $uri);
				}
			}
		}

		return parent::configure();
	}

	#[\Override]
	public function execute(string $url, array $params = []) : mixed {
		$response = parent::execute($url, $params);

		if (!isset($response) || $response == null)
			return null;
		
		if ($this->existsUrl($_SERVER['REQUEST_METHOD'], $url)) {
			header("Content-Type: application/json; charset=UTF-8");
			echo $response;	
		} else {
			$this->loader->load($response['page'], $response['data']);
		}

		return $response;
	}

	private function existsUrl(string $method, string $url) : bool {
		if ($method == null || $url == null)
			return false;

		if (!isset($this->routes[$method]))
			return false;

		foreach ($this->routes[$method] as $u) {
			if ($u == $url) return true;
		}

		return false;
	}

	private function addRoute(string $method, string $route) {
		if ($method == null || $route == null)
			return;

		if (!isset($this->routes[$method]))
			$this->routes[$method] = [];
		
		$this->routes[$method][] = $route;
	}

}