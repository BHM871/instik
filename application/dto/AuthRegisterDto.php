<?php

namespace Instik\DTO;

class AuthRegisterDto {

	private string $email;
	private string $password;
	private string $confirm;

	public function __construct(string $email, string $password, string $confirm) {
		$this->email = $email;
		$this->password = $password;
		$this->confirm = $confirm;
	}

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