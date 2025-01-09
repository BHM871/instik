<?php

class ViewLoader {

	private $ths;

	private function __constructor(){}

	public static function instance(): ViewLoader {
		if (isset($ths))
			return $ths;

		$ths = new ViewLoader();
		return $ths;
	}

	public function load($view, $data = array()) : void {
		$view = './views/'.$view.'.php';
		
		if (file_exists($view)) {
			extract($data);
			include($view);
			return;
		}

		include("./views/errors/NotFound.php");
	}
}