<?php

namespace Instik\DTO;

class AuthChangePasswordDto {

	public function __construct(
		private readonly string $hash, 
		private readonly string $password, 
		private readonly string $confirm
	) {}

	public function getHash() : string {
		return $this->hash;
	}

	public function getPassword() : string {
		return $this->password;
	}

	public function getConfirm() : string {
		return $this->confirm;
	}

}