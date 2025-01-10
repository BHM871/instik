<?php

class IController {
	/*
		system/ViewLoader
	*/
	protected $loader;

	public function __construct()
	{
		$this->loader = ViewLoader::instance();
	}
}