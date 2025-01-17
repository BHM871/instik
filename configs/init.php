<?php
require_once("./configs/constants.php");
require_once("./configs/enums.php");

require_once("./system/ClassLoader.php");
require_once("./system/ViewLoader.php");
require_once("./system/Instancer.php");
require_once("./system/RouterConfig.php");
require_once("./system/Database.php");

require_once("./application/init.php");

ClassLoader::load('./application');
ClassLoader::load_env();
RouterConfig::configure();
Database::setup();