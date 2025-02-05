<?php

namespace Instik\DTO\Entity;

use Instik\Entity\User;

use ReflectionClass;

class UserDto {

	public function __construct(
		private readonly int $id,
		private readonly string $username,
		private readonly string $email,
		private readonly ?string $image_path = null
	) {}

	public static function by(User $user) : ?self {
		if ($user == null || $user->getId() == null)
			return null;

		return new UserDto(
			id: $user->getId(),
			username: $user->getUsername(),
			email: $user->getEmail(),
			image_path: $user->getImagePath()
		);
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

}