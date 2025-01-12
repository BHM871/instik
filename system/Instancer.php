<?php

class Instancer {

	private static $instances = array();

	public static function get($class) {
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
					return Instancer::get($reflec->getName());
				}
			}

			throw new Exception("Class is not instanceable");
		}

		if ($reflection->getConstructor() == null) {
			return Instancer::instance($reflection);
		}

		$dependencies = array();
		foreach ($reflection->getConstructor()->getParameters() as $parameter) {
			$dependencies[] = Instancer::get($parameter->getType()->getName());
		}

		return Instancer::instance($reflection, $dependencies);
	}

	private static function instance(ReflectionClass $reflection, array $dependencies = array()) {
		$object = $reflection->newInstanceArgs($dependencies);
		Instancer::$instances[$reflection->getName()] = $object;
		return $object;
	}

}