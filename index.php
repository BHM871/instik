<?php
require_once("./configs/init.php");

$uri = $_SERVER['REQUEST_URI'];
$uri = parse_url($uri, PHP_URL_PATH);

RouterConfig::submit($uri);
Database::instance()->query("DROP TABLE a");