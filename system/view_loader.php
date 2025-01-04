<?php

class ViewLoader {

	private $ths;

	private function __constructor(){}

	public static function instance() {
		if (isset($ths))
			return $ths;

		$ths = new ViewLoader();
		return $ths;
	}

	public function load($view) : void {
		$view = './views/'.$view.'.php';
		
		if (file_exists($view)) {
			include($view);
			return;
		}

		include("./views/errors/NotFoundView.html");
	}
}