<?php

class Logger {

	private ReflectionClass $class;

	public function __construct(object $class)
	{
		$this->class = new ReflectionClass(get_class($class));
	}

	public function log($message) {
	    $message = date("H:i:s") . $this->class->getName() . " - $message - ".PHP_EOL;
	    print($message);
	    flush();
	    ob_flush();
	}

}