<?php

namespace System\Interfaces\Request;

abstract class Layer {

	public function __construct(
		protected readonly ?Layer $next = null
	) {}

	public abstract function setupToClassName(string $className);		

	public function execute(string $url, array $params = []) : mixed {
		if ($this->next == null)
			return true;

		return $this->next->execute($url, $params);
	}

}