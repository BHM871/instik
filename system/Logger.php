<?php

namespace System;

use ReflectionClass;

class Logger {

	private ReflectionClass $class;

	public function __construct(object $class)
	{
		$this->class = new ReflectionClass(get_class($class));
	}

	public function log($message) {
		if (!file_exists(LOG_PATH)) {
			file_put_contents(LOG_PATH, "");
		}

	    $message = date("H:i:s") . " - " . $this->class->getFileName() . " - $message - ";
		file_put_contents(LOG_PATH, file_get_contents(LOG_PATH) . PHP_EOL . $message);
	}

}