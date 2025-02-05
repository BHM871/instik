<?php

use System\Core\ClassLoader;
use System\Core\Instancer;
use System\Database;
use System\Request\RequestChain;
use System\Request\ResponseLayer;
use System\Request\RouterLayer;
use System\Security\SecurityLayer;

require_once("./configs/constants.php");
require_once("./configs/enums.php");

require_once("./system/core/ClassLoader.php");

ClassLoader::load('./system');
ClassLoader::load('./libs');
ClassLoader::load('./application');
ClassLoader::load_env();

RequestChain::$observators[] = Instancer::get(ResponseLayer::class);
RequestChain::$observators[] = Instancer::get(SecurityLayer::class);
RequestChain::$observators[] = Instancer::get(RouterLayer::class);
RequestChain::configure();

require_once("./application/init.php");

Database::setup();