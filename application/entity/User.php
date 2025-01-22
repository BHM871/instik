<?php

namespace Instik\Entity;

class User {

	public function __construct(
		private ?int $id = null,
		private ?string $username = null,
		private ?string $email = null,
		private ?string $password = null,
		private ?int $is_valid = null,
		private ?string $imagePath = null,
		private ?string $hash_change_password = null
	) {}

	public static function instancer(array $user) : ?self {
		if ($user == null) return null;

		$id = isset($user['id']) ? $user['id'] : null;
		$username = isset($user['username']) ? $user['username'] : null;
		$email = isset($user['email']) ? $user['email'] : null;
		$password = isset($user['password']) ? $user['password'] : null;
		$isValid = isset($user['is_valid']) ? $user['is_valid'] : null;
		$imagePath = isset($user['imagePath']) ? $user['imagePath'] : null;
		$hash = isset($user['hash_change_password']) ? $user['hash_change_password'] : null;

		return new User($id, $username, $email, $password, $isValid, $imagePath, $hash);
	}

	function getId() : ?int {
		return $this->id;
	}

	function getUsername() : ?string {
		return $this->username;
	}

	function getEmail() : ?string {
		return $this->email;
	}

	function getPassword() : ?string {
		return $this->password;
	}

	function getIsValid() : ?bool {
		return $this->is_valid;
	}

	function getImagePath() : ?string {
		return $this->imagePath;
	}

	function getHash() : ?string {
		return $this->hash_change_password;
	}

}