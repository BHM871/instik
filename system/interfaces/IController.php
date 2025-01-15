<?php

abstract class IController {
	/*
		system/ViewLoader
	*/
	protected $loader;

	public function __construct()
	{
		$this->loader = ViewLoader::instance();
	}

	protected function redirect($path) {
		$path = preg_replace("/^(\/)/", "", $path);
		header("Location: " . BASE_URL . "/$path");
	}

}