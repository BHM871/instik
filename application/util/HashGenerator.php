<?php

namespace Instik\Util;

use Exception;

class HashGenerator {

	private static string $cipher = 'seed-cbc';

	public static function encrypt($value) : string {
		if ($value == null || !is_string($value) || strlen($value) == 0) {
			return ""; 
		}

		if (!isset(env['HASH_SEED'])) {
			throw new Exception("Cannot create hash");
		}

		return openssl_encrypt($value, HashGenerator::$cipher, env['HASH_SEED'], 0, HashGenerator::getIv());
	}

	public static function decrypt($hash) : string {
		if ($hash == null || !is_string($hash) || strlen($hash) == 0) {
			return ""; 
		}

		if (!isset(env['HASH_SEED'])) {
			throw new Exception("Cannot create hash");
		}

		return openssl_decrypt($hash, HashGenerator::$cipher, env['HASH_SEED'], 0, HashGenerator::getIv());
	}

	private static function getIv() : string {
		return "WkE}b6Jk-T&6Y%5?"; 	
	}

}