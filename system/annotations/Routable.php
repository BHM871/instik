<?php

#[Attribute(Attribute::TARGET_CLASS)]
class Routable {

	public string $name;

	public function __construct(string $name = "") {
		$this->$name = $name;
	}

}