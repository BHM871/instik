<?php

namespace Instik\Services;

use Instik\DTO\FeedFiltersDto;
use Instik\Repository\PostRepository;

class PostService {

	public function __construct(
		private readonly PostRepository $repository
	) {}

	public function getFeed(int $userId, ?FeedFiltersDto $filters = null) : ?array {
		if ($userId == null || $userId <= 0)
			return null;

		$posts = $this->repository->getPostsByUser($userId, $filters);

		if ($posts == null || sizeof($posts) == 0)
			return null;

		return $posts;
	}

	public function postIsLikedByUser(int $postId, int $userId) : bool {
		if ($postId == null || $userId == null)
			return false;

		return $this->repository->postIsLikedByUser($postId, $userId);
	}
}