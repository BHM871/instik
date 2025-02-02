<?php

namespace System\Interfaces\Application;

use System\Core\ViewLoader;
use System\Security\SessionManager;

abstract class IController {

	protected SessionManager $session;

	public function __construct(SessionManager $session) {
		$this->session = $session;
	}

	protected function returnPage(string $page, array $data) : array {
		return ['page' => $page, 'data' => $data];
	}

	protected function returnJson(object|array $data) : string {
		return json_encode($data);	
	}

	protected function redirect($path) {
		$path = preg_replace("/^(\/)/", "", $path);
		header("Location: " . BASE_URL . "/$path");
	}

}