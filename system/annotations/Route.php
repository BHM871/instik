<?php

#[Attribute(Attribute::TARGET_METHOD)]
class Route {

	public string $value;
	public array|string $methods;
	public bool $needAuthentication;

	public const GET = 'GET';
	public const POST = 'POST';

	public function __construct(string $value, array|string $methods = [Route::GET], bool $needAuthentication = false) {
		$this->value = $value;
		$this->methods = $methods;		
		$this->needAuthentication = $needAuthentication;
	}

	public function getValue() : string {
		return $this->value;
	}

	public function getMethods() : array|string {
		return $this->methods;
	}

	public function getNeedAuthentication() : bool {
		return $this->needAuthentication;
	}

}