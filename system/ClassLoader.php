<?php

class ClassLoader {

	public static function load($path) : array {
		$classes = [];
		$iterator = new DirectoryIterator($path);

		foreach ($iterator as $info) {
			if ($info->getFilename() == '.' || $info->getFilename() == '..' || $info->getFilename() == 'views') {
				continue;
			}

			if ($info->isDir()) {
				ClassLoader::load($info->getPathname());
				continue;
			}

			$classes[] = $info->getPathname();
			include($info->getPathname());
		}

		return $classes;
	}

}