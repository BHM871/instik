<?php

namespace Instik\DTO;

class AuthRegisterDto {

	public function __construct(
		private readonly string $email,
		private readonly string $password,
		private readonly string $confirm
	) {}

	public function getEmail() : string {
		return $this->email;
	}

	public function getPassword() : string {
		return $this->password;
	}

	public function getConfirm() : string {
		return $this->confirm;
	}

}