<?php

abstract class IController {
	/*
		system/ViewLoader
	*/
	protected ViewLoader $loader;
	protected SessionManager $session;

	public function __construct(SessionManager $session) {
		$this->loader = ViewLoader::instance();
		$this->session = $session;
	}

	protected function redirect($path) {
		$path = preg_replace("/^(\/)/", "", $path);
		header("Location: " . BASE_URL . "/$path");
	}

}