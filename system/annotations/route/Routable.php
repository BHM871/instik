<?php

namespace System\Annotations\Route;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class Routable {

	public function __construct(
		private readonly string $name = "/"
	) {}

	public function getName() : string {
		return $this->name;
	}

}