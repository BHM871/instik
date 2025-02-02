<?php

use System\Core\ClassLoader;
use System\Database;
use System\Request\RequestManager;

require_once("./configs/constants.php");
require_once("./configs/enums.php");

require_once("./system/core/ClassLoader.php");

ClassLoader::load('./system');
ClassLoader::load('./libs');
ClassLoader::load('./application');
ClassLoader::load_env();
RequestManager::configure();

require_once("./application/init.php");

Database::setup();