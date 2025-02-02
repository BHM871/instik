<?php

namespace System\Annotations\Route;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
class Route {

	public const GET = 'GET';
	public const POST = 'POST';

	public function __construct(
		private readonly string $value, 
		private readonly array|string $methods = [Route::GET]
	) {}

	public function getValue() : string {
		return $this->value;
	}

	public function getMethods() : array|string {
		return $this->methods;
	}

}