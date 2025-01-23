<?php

namespace System\Core;

use System\Logger;

use DirectoryIterator;
use Exception;

class ClassLoader {

	public static function load($path, $counter = 0) : array {
		if ($counter > 50) {
			throw new Exception("Cannot load all classes");
		}

		$classes = [];
		$tryAgain = [];
		$iterator = new DirectoryIterator($path);

		foreach ($iterator as $info) {
			if ($info->getFilename() == '.' || $info->getFilename() == '..' || preg_match("/".str_replace("/", "\/", VIEWS_PATH)."/", $info->getPathname())) {
				continue;
			}

			if ($info->isDir()) {
				ClassLoader::load($info->getPathname());
				continue;
			}

			if ($info->getExtension() != 'php' || $info->getFilename() == "ClassLoader.php") {
				continue;
			}

			try {
				include_once($info->getPathname());
				$classes[$info->getPath()][] = $info->getPathname();
			} catch (\Throwable $th) {
				$tryAgain[] = $info->getPath();
			}
		}

		foreach ($tryAgain as $path) {
			ClassLoader::load($path, $counter+1);
		}

		return $classes;
	}

	public static function load_env() {
		if (!file_exists(ENV_PATH)) {
			return;
		}

		$map = [];
		try {
			$env = file_get_contents(ENV_PATH);
			$env = preg_split("/\\n/", $env);
			$map = [];
			foreach ($env as $values){
				if ($values == "") continue;
				
				$t = preg_split("/\=/", $values);
				$map[$t[0]] = $t[1];
			}
		} catch (\Throwable $t) {
			(new Logger(new ClassLoader))->log("Cannot load env file");

			$map = [];
		}
		
		define("env", $map);
	}

}