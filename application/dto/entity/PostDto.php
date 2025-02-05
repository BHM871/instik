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
		private readonly ?int $likes = 0,
		private readonly ?UserDto $publisher = null,
		private readonly ?array $comments = null,
		private readonly bool $isLike = false
	) {}

	public static function by(?Post $post) : ?self {
		if ($post == null || $post->getId() == null)
			return null;

		$comments = [];
		if ($post->getComments() != null) {
			foreach ($post->getComments() as $comment) {
				$comments[] = CommentDto::by($comment);
			}
		}

		return new PostDto(
				id: $post->getId(),
				caption: $post->getCaption(),
				image_path: $post->getImagePath(),
				likes: $post->getLike() != null ? $post->getLike() : 0,
				publisher: UserDto::by($post->getPublisher()),
				comments: $comments
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
				else if (is_array($value) && !empty($value) && $value[0] instanceof CommentDto) {
					$comments = [];
					foreach($value as $comment) {
						$comments[] = $comment->toArray();
					}

					$array[$property->getName()] = $comments;
				}
				else
					$array[$property->getName()] = $value;
			}
		}

		return $array;
	}

}