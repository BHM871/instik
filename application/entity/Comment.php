<?php

namespace Instik\Entity;

use DateTime;
use ReflectionClass;

class Comment {

	private const DATE_FORMAT = "Y-m-d H:i:s";

	public function __construct(
		private readonly ?int $id = null,
		private readonly ?DateTime $comment_date = null,
		private readonly ?string $content = null,
		private readonly ?Post $post = null,
		private readonly ?User $user = null
	) {}

	public static function instancer(?array $comment) : ?self {
		if ($comment == null || sizeof($comment) == 0)
			return null;

		$id = Comment::getValueFromArray($comment, 'id');
		$comment_date = Comment::getDateTimeFromArray($comment);
		$content = Comment::getValueFromArray($comment, 'content');
		$post = Comment::getPostFromArray($comment);
		$commenter = Comment::getUserFromArray($comment);

		return new Comment($id, $comment_date, $content, $post, $commenter);
	}

	public function toArray() : array {
		$array = [];

		$reflection = new ReflectionClass($this);
		foreach ($reflection->getProperties() as $property) {
			$value = $property->getValue($this);
			if ($value != null) {
				if ($value instanceof User)
					$array[$property->getName()] = $value->toArray();
				else
					$array[$property->getName()] = $value;
			}
		}

		return $array;
	}

	private static function getValueFromArray(array $comment, string $key) : mixed {
		return isset($comment[$key]) ? $comment[$key] : null;
	}

	private static function getDateTimeFromArray(array $comment) : ?DateTime {
		if (!isset($comment['comment_date'])) return null;

		$value = $comment['comment_date'];
		if (is_string($value))
			return DateTime::createFromFormat(Comment::DATE_FORMAT, $value);
		
		if ($value instanceof DateTime)
			return $value;

		return null;
	}

	private static function getPostFromArray(array $comment) : ?Post {
		if (!isset($comment['id_post']) && !isset($comment['post'])) return null;

		if (isset($comment['id_post']) && is_int($comment['id_post'])) {
			return new Post(id: $comment['id_post']);
		}
		
		if (isset($comment['post'])) {
			$value = $comment['post'];		
			
			if (is_array($value))
				return Post::instancer($value);

			if ($value instanceof Post)
				return $value;
		}

		return null;
	}

	private static function getUserFromArray(array $comment) : ?User {
		if (!isset($comment['id_commenter']) && !isset($comment['user'])) return null;

		if (isset($comment['id_commenter']) && is_int($comment['id_commenter'])) {
			return new User(id: $comment['id_commenter']);
		}
		
		if (isset($comment['user'])) {
			$value = $comment['user'];		
			
			if (is_array($value))
				return User::instancer($value);

			if ($value instanceof User)
				return $value;
		}

		return null;
	}

	public function getId() : int {
		return $this->id;
	}

	public function getCommentDate() : ?DateTime {
		return $this->comment_date;
	}

	public function getContent() : ?string {
		return $this->content;
	}

	public function getPost() : ?Post {
		return $this->post;
	}

	public function getUser() : ?User {
		return $this->user;
	}

}