<?php

namespace Instik\Entity;

use DateTime;
use Instik\Entity\User;
use ReflectionClass;

class Post {

	private const DATE_FORMAT = "Y-m-d H:i:s";

	public function __construct(
		private readonly ?int $id = null,
		private readonly ?DateTime $posted_date = null,
		private readonly ?string $caption = null,
		private readonly ?string $image_path = null,
		private readonly ?int $like = null,
		private readonly ?User $publisher = null
	) {}

	public static function instancer(?array $post) : ?self {
		if ($post == null || sizeof($post) == 0)
			return null;

		$id = Post::getValueFromArray($post, 'id');
		$posted_date = Post::getDateTimeFromArray($post);
		$caption = Post::getValueFromArray($post, 'caption');
		$image_path = Post::getValueFromArray($post, 'image_path');
		$like = Post::getValueFromArray($post, 'like');
		$publisher = Post::getUserFromArray($post);

		return new Post($id, $posted_date, $caption, $image_path, $like, $publisher);
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

	private static function getValueFromArray(array $post, string $key) : mixed {
		return isset($post[$key]) ? $post[$key] : null;
	}

	private static function getDateTimeFromArray(array $post) : ?DateTime {
		if (!isset($post['posted_date'])) return null;

		$value = $post['posted_date'];
		if (is_string($value))
			return DateTime::createFromFormat(Post::DATE_FORMAT, $value);
		
		if ($value instanceof DateTime)
			return $value;

		return null;
	}

	private static function getUserFromArray(array $post) : ?User {
		if (!isset($post['id_publisher']) && !isset($post['user'])) return null;

		if (isset($post['id_publisher']) && is_int($post['id_publisher'])) {
			return new User(id: $post['id_publisher']);
		}
		
		if (isset($post['user'])) {
			$value = $post['user'];		
			
			if (is_array($value))
				return User::instancer($value);

			if ($value instanceof User)
				return $value;
		}

		return null;
	}

	public function getId() : ?int {
		return $this->id;
	}

	public function getPostedDate() : ?DateTime {
		return $this->posted_date;
	}

	public function getCaption() : ?string {
		return $this->caption;
	}

	public function getImagePath() : ?string {
		return $this->image_path;
	}

	public function getLike() : ?int {
		return $this->like;
	}

	public function getPublisher() : ?User {
		return $this->publisher;
	}

}