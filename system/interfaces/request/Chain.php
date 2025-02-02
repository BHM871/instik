<?php

namespace System\Interfaces\Request;

abstract class Chain {

	public function __construct(
		protected readonly ?Chain $next = null
	) {}

	public function configure() : bool {
		if ($this->next == null)
			return true;

		return $this->next->configure();
	}

	public function execute(string $url, array $params = []) : mixed {
		if ($this->next == null)
			return true;

		return $this->next->execute($url, $params);
	}

}