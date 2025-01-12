<?php
require_once("./configs/constants.php");
require_once("./configs/enums.php");

require_once("./system/ClassLoader.php");
require_once("./system/ViewLoader.php");
require_once("./system/Instancer.php");
require_once("./system/RouterConfig.php");

ClassLoader::load('./application');
RouterConfig::configure();