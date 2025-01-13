<?php

class ViewLoader {

	private static $ths;

	public static function instance(): ViewLoader {
		if (isset(ViewLoader::$ths))
			return ViewLoader::$ths;

		ViewLoader::$ths = new ViewLoader();
		return ViewLoader::$ths;
	}

	public function load($view, $data = array()) : void {
		$view = VIEWS_PATH.'/'.$view.'.php';
		
		if (file_exists($view)) {
			extract($data);
			include($view);
			return;
		}

		include(VIEWS_PATH."/errors/".ErrorsPaths::notFound."php");
	}
}