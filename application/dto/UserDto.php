<?php

class UserDto {

	private int $id;
	private string $email;
	private string $username;

	public function __construct(int $id, string $email, string $username) {
		$this->id = $id;
		$this->email = $email;
		$this->username = $username;
	}

	public function getId() : int {
		return $this->id;
	}

	public function getEmail() : string {
		return $this->email;
	}

	public function getUsername() : string {
		return $this->username;
	}

}