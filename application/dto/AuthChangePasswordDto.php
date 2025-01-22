<?php

namespace Instik\DTO;

class AuthChangePasswordDto {

	private string $hash;
	private string $password;
	private string $confirm;

	public function __construct(string $hash, string $password, string $confirm) {
		$this->hash = $hash;
		$this->password = $password;
		$this->confirm = $confirm;
	}

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