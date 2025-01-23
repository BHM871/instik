<?php

use System\Core\ClassLoader;
use System\Database;
use System\RouterConfig;

require_once("./configs/constants.php");
require_once("./configs/enums.php");

require_once("./system/core/ClassLoader.php");

ClassLoader::load('./system');
ClassLoader::load('./libs');
ClassLoader::load('./application');
ClassLoader::load_env();
RouterConfig::configure();
Database::setup();

require_once("./application/init.php");