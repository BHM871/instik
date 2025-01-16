<?php

class ClassLoader {

	public static function load($path) : array {
		$classes = [];
		$iterator = new DirectoryIterator($path);

		foreach ($iterator as $info) {
			if ($info->getFilename() == '.' || $info->getFilename() == '..' || preg_match("/".str_replace("/", "\/", VIEWS_PATH)."/", $info->getPathname())) {
				continue;
			}

			if ($info->isDir()) {
				ClassLoader::load($info->getPathname());
				continue;
			}

			if ($info->getExtension() != 'php') {
				continue;
			}

			$classes[] = $info->getPathname();
			include($info->getPathname());
		}

		return $classes;
	}

}