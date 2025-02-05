<?php

namespace Instik\DTO\Entity;

use Instik\Entity\Post;
use Instik\Entity\User;

use ReflectionClass;

class PostDto {

	private function __construct(
		private readonly int $id,
		private readonly ?string $caption = null,
		private readonly ?string $image_path = null,
		private readonly int $likes = 0,
		private readonly UserDto $publisher,
		private readonly bool $isLike = false
	) {}

	public static function by(Post $post) : ?self {
		if ($post == null || $post->getId() == null)
			return null;

		return new PostDto(
				id: $post->getId(),
				caption: $post->getCaption(),
				image_path: $post->getImagePath(),
				likes: $post->getLike(),
				publisher: UserDto::by($post->getPublisher())
		);
	}

	public function toArray() : array {
		$array = [];

		$reflection = new ReflectionClass($this);
		foreach ($reflection->getProperties() as $property) {
			$value = $property->getValue($this);
			if ($value != null) {
				if ($value instanceof UserDto)
					$array[$property->getName()] = $value->toArray();
				else
					$array[$property->getName()] = $value;
			}
		}

		return $array;
	}

}