<?php

namespace System\Request;

use Configs\ErrorsPaths;

use System\Core\Instancer;
use System\Core\ViewLoader;
use System\Interfaces\Request\Chain;
use System\Logger;

class RequestManager {

	private static ?Chain $first = null;
	private static bool $isConfigured = false;

	public static function configure() {
		if (RequestManager::$isConfigured)
			return;

		RequestManager::$first = Instancer::get(ResponseManager::class);

		if (RequestManager::$first == null)
			return;

		RequestManager::$isConfigured = RequestManager::$first->configure();
	}

	public static function submit() : mixed {
		if (!RequestManager::$isConfigured)
			RequestManager::configure();

		if (RequestManager::$first == null) {
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

			return RequestManager::$first->execute($uri);
		} catch (\Throwable $th) {
			(new Logger(new RequestManager))->log($th);
			ViewLoader::instance()->load(ErrorsPaths::internalServerError);
		}
	}

}