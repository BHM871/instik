<?php

#[Attribute(Attribute::TARGET_METHOD)]
class Route {

	public string $name;
	public array $methods;

	public const GET = 'GET';
	public const POST = 'POST';

	public function __construct(string $name, array $methods = [Route::GET]) {
		$this->$name = $name;
		$this->$methods = $methods;		
	}

}