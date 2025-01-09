<?php
require_once("./configs/constants.php");
require_once("./configs/enums.php");

require_once("./system/ViewLoader.php");
require_once("./system/RouterConfig.php");

function loadClasses($path) {
	$iterator = new DirectoryIterator($path);

	foreach ($iterator as $info) {
		if ($info->getFilename() == '.' || $info->getFilename() == '..') {
			continue;
		}

		if ($info->isDir()) {
			loadClasses($info->getPathname());
			continue;
		}

		$GLOBALS['classesToLoad'][] = $info->getPathname();
	}
}

function addRoutes() {
	foreach (get_declared_classes() as $className) {
		$class = new ReflectionClass($className);
		$attributes = $class->getAttributes(Routable::class);
		
		if (empty($attributes)) {
			continue;
		}

		$object = $class->newInstance();

		$classUri = $attributes[0]->getArguments()[0];
		$classUri = preg_replace("/^[^\/]/", '/', $classUri);
		$classUri = preg_replace("/\/$/", '', $classUri);

		foreach ($class->getMethods() as $method) {
			$attributes = $method->getAttributes(Route::class);

			if(empty($attributes) || $method->isConstructor() || $method->isDestructor())
				continue;

			$arguments = $attributes[0]->getArguments();

			$methodUri = $arguments[0];
			$methodUri = preg_replace("/^[^\/]/", '/', $methodUri);
			$methodUri = preg_replace("/\/$]/", '', $methodUri);

			$realPath = $classUri.$methodUri;
			RouterConfig::add($realPath, $arguments[1], $object, $method);
		}
	}
}

loadClasses("./application");

foreach($classesToLoad as $classPath) {
	include($classPath);
}

addRoutes();