<?php

namespace Instik\Entity;

use ReflectionClass;

class User {

	public function __construct(
		private int $id = null,
		private ?string $username = null,
		private ?string $email = null,
		private ?string $password = null,
		private ?bool $is_valid = null,
		private ?string $image_path = null,
		private ?string $hash_change_password = null
	) {}

	public static function instancer(array $user) : ?self {
		if ($user == null) return null;

		$id = User::getValueFromArray($user, 'id');
		$username = User::getValueFromArray($user, 'username');
		$email = User::getValueFromArray($user, 'email');
		$password = User::getValueFromArray($user, 'password');
		$is_valid = User::getValueFromArray($user, 'is_valid');
		$image_path = User::getValueFromArray($user, 'image_path');
		$hash = User::getValueFromArray($user, 'hash_change_password');

		return new User($id, $username, $email, $password, $is_valid, $image_path, $hash);
	}

	public function toArray() : array {
		$array = [];
		
		$reflection = new ReflectionClass($this);
		foreach ($reflection->getProperties() as $prop) {
			$value = $prop->getValue($this);
			if ($value != null)
				$array[$prop->getName()] = $value;
		}

		return $array;
	}

	private static function getValueFromArray(array $user, string $key) : mixed {
		return isset($user[$key]) ? $user[$key] : null;
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
		return $this->image_path;
	}

	function getHash() : ?string {
		return $this->hash_change_password;
	}

}