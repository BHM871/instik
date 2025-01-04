<?php
require_once("./configs/init.php");

$loader = ViewLoader::instance();

if (isset($_COOKIE[Cookies::$session]))
	$loader->load("inicital_page");	

$loader->load("login");