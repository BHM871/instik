<?php

class HashGenerator {

	public static function generate($value) : string {
		if ($value == null || strlen($value) == 0) {
			return ""; 
		}

		if (!isset(env['HASH_SEED'])) {
			throw new Exception("Cannot create hash");
		}

		return hash("gost", $value, false, ['seed' => env['HASH_SEED']]);
	}

}