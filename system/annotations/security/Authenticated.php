<?php

namespace System\Annotations\Security;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
class Authenticated {

	public function __construct() {}

}