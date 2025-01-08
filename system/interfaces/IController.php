<?php

class IController {
	/*
		system/ViewLoader
	*/
	protected $loader;

	public function __constructor() {
		require_once("./configs/init.php");
		$this->loader = ViewLoader::instance();
	}
}