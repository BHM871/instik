<?php

namespace Instik\DTO\Entity;

use DateTime;
use Instik\Entity\Comment;
use Instik\Entity\Post;
use Instik\Entity\User;
use ReflectionClass;

class CommentDto {

	public function __construct(
		private readonly int $id,
		private readonly DateTime $comment_date,
		private readonly string $content,
		private readonly PostDto $post,
		private readonly UserDto $user
	) {}

	public static function by(?Comment $comment) : ?self {
		if ($comment == null || $comment->getId() == null)
			return null;

		return new CommentDto(
			id: $comment->getId(),
			comment_date: $comment->getCommentDate(),
			content: $comment->getContent(),
			post: PostDto::by($comment->getPost()),
			user: UserDto::by($comment->getUser())
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
				else if ($value instanceof PostDto)
					$array[$property->getName()] = $value->toArray();
				else
					$array[$property->getName()] = $value;
			}
		}

		return $array;
	}

}