<?php

class ViewLoader {
	function load($view) : void {
		$view = './views/'.$view.'.php';
		
		if (file_exists($view)) {
			include($view);
			return;
		}

		include("./pages/errors/NotFoundView.html");
	}
}