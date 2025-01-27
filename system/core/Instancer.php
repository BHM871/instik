<?php

namespace System\Core;

use ReflectionClass;
use Exception;
use ReflectionAttribute;

class Instancer {

	private static $instances = array();

	public static function get($class, $counter = 0) {
		if ($counter > 50) {
			throw new Exception("Many recursion");
		}

		if (!is_string($class) && !is_object($class) && !preg_match("/ReflectionClass/", get_class($class))) {
			throw new Exception("Parameter type is invalid");
		}

		if (is_string($class) && isset($instances[$class])) {
			return $instances[$class];
		}

		$reflection = is_string($class)
			? new ReflectionClass($class)
			: $class;

		if ($reflection->isAbstract() || $reflection->isInterface()) {
			foreach (get_declared_classes() as $clazz) {
				$reflec = new ReflectionClass($clazz);

				if ($reflec->getParentClass() != false && $reflec->getParentClass()->getName() == $reflection->getName()) {
					return Instancer::get($reflec->getName(), $counter+1);
				}
			}

			throw new Exception("Class is not instanceable");
		}

		if ($reflection->getConstructor() == null) {
			return Instancer::instance($reflection);
		}

		$dependencies = array();
		foreach ($reflection->getConstructor()->getParameters() as $parameter) {
			$dependencies[] = Instancer::get($parameter->getType()->getName(), $counter+1);
		}

		return Instancer::instance($reflection, $dependencies);
	}
	
	public static function getByReflectionAttribute(ReflectionAttribute $reflection) : object {
		if ($reflection == null)
			return null;

		$class = new ReflectionClass($reflection->getName());
		return $class->newInstanceArgs($reflection->getArguments());
	}

	private static function instance(ReflectionClass $reflection, array $dependencies = array()) {
		$object = $reflection->newInstanceArgs($dependencies);
		Instancer::$instances[$reflection->getName()] = $object;
		return $object;
	}

}