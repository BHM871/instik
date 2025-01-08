<?php
require_once("./configs/init.php");

$loader = ViewLoader::instance();

if (isset($_COOKIE[Cookies::$session])) {
	$loader->load(Pages::$home);	
	return;
}

$loader->load(Pages::$login);