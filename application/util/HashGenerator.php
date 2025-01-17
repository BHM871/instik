<?php

class HashGenerator {

	private static string $cipher = 'seed-cbc';

	public static function encrypt($value) : string {
		if ($value == null || !is_string($value) || strlen($value) == 0) {
			return ""; 
		}

		if (!isset(env['HASH_SEED'])) {
			throw new Exception("Cannot create hash");
		}

		$ivlen = openssl_cipher_iv_length(HashGenerator::$cipher);
		$iv = openssl_random_pseudo_bytes($ivlen);
		return openssl_encrypt($value, HashGenerator::$cipher, env['HASH_SEED'], 0, $iv);
	}

	public static function decrypt($hash) : string {
		if ($hash == null || !is_string($hash) || strlen($hash) == 0) {
			return ""; 
		}

		if (!isset(env['HASH_SEED'])) {
			throw new Exception("Cannot create hash");
		}

		$ivlen = openssl_cipher_iv_length(HashGenerator::$cipher);
		$iv = openssl_random_pseudo_bytes($ivlen);
		return openssl_decrypt($hash, HashGenerator::$cipher, env['HASH_SEED'], 0, $iv);
	}

}