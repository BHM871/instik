<?php

namespace Instik\DTO;

class UserConfirmRegisterDto {

	public function __construct(
		private readonly int $id,
		private readonly string $username
	) {}

	public function getId() : int {
		return $this->id;
	}

	public function getUsername() : string {
		return $this->username;
	}

}