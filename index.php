<?php
require_once("./configs/init.php");

$loader = new ViewLoader();

if (isset($_COOKIE[Cookies::$session]))
	$loader->load("inicital_page");	

$loader->load("login");