<?php

namespace System\Request;

use Configs\ErrorsPaths;

use System\Core\Instancer;
use System\Core\ViewLoader;
use System\Interfaces\Request\Layer;
use System\Logger;

class RequestChain {

	private static ?Layer $first = null;
	private static bool $isConfigured = false;

	public static function configure() {
		if (RequestChain::$isConfigured)
			return;

		RequestChain::$first = Instancer::get(ResponseLayer::class);

		if (RequestChain::$first == null)
			return;

		RequestChain::$isConfigured = RequestChain::$first->configure();
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