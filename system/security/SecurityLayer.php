<?php

namespace System\Security;

use Configs\ErrorsPaths;

use System\Annotations\Route\Routable;
use System\Annotations\Route\Route;
use System\Annotations\Security\Authenticated;
use System\Interfaces\Request\Layer;
use System\Request\RouterLayer;

use ReflectionClass;

class SecurityLayer extends Layer {

	private array $visitedClass = [];
	private array $routes = [];

	public function __construct(
		RouterLayer $next,
		private readonly SessionManager $session
	) {
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
				$attributes = $method->getAttributes(Authenticated::class);

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
		if (!isset($this->routes[$_SERVER['REQUEST_METHOD']]))
			return parent::execute($url, $params);
		
		if (!$this->existsUrl($_SERVER['REQUEST_METHOD'], $url))
			return parent::execute($url, $params);
		
		if (!$this->session->isAuthenticated())
			return ErrorsPaths::forbbiden;

		return parent::execute($url, $params); 
	}

	private function addRoute(string $method, string $route) {
		if ($method == null || $route == null)
			return;

		if (!isset($this->routes[$method]))
			$this->routes[$method] = [];
		
		$this->routes[$method][] = $route;
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

}