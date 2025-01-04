<?php

class ViewLoader {
	static function load($view) : void {
		$view = './pages/'.$view.'.php';
		
		if (file_exists($view)) {
			include($view);
			return;
		}

		include("./pages/errors/NotFoundView.html");
	}
}