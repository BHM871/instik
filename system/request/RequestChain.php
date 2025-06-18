<?php

namespace System\Request;

use Configs\ErrorsPaths;

use System\Core\Instancer;
use System\Core\ViewLoader;
use System\Interfaces\Request\Layer;
use System\Logger;

class RequestChain {

	public static array $observators = [];
	private static ?Layer $first;
	private static bool $isConfigured = false;

	private static array $visitedClass = [];

	public static function configure() {
		if (RequestChain::$isConfigured)
			return;

		foreach (get_declared_classes() as $className) {
			if (isset(RequestChain::$visitedClass[$className]) && RequestChain::$visitedClass[$className])
				continue;

			RequestChain::$visitedClass[$className] = true;

			foreach (RequestChain::$observators as $obs) {
				$obs->setupToClassName($className);
			}
		}

		RequestChain::$first = Instancer::get(ResponseLayer::class);
		RequestChain::$isConfigured = true;
	}

	public static function submit() : mixed {
		if (!RequestChain::$isConfigured)
			RequestChain::configure();

		if (RequestChain::$first == null) {
			ViewLoader::instance()->load(ErrorsPaths::internalServerError);
			return null;
		}

		try {
			$uri = $_SERVER['REQUEST_URI'];
			$uri = parse_url($uri, PHP_URL_PATH);

			preg_match_all("/\/\w+/", $uri, $occurences);
			foreach ($occurences as $occu) {
				foreach ($occu as $oc) {
					$oc = '/'.preg_replace('/\//', '\/', $oc).'/';
					if (preg_match($oc, __DIR__)) {
						$uri = preg_replace($oc, "", $uri);
					}
				}
			}

			return RequestChain::$first->execute($uri);
		} catch (\Throwable $th) {
			(new Logger(new RequestChain))->log($th);
			ViewLoader::instance()->load(ErrorsPaths::internalServerError);
			return null;
		}
	}

}