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