<?php

namespace Instik\Services;

use Instik\DTO\FeedFiltersDto;
use Instik\Entity\Post;
use Instik\Repository\LikeRepository;
use Instik\Repository\PostRepository;

class PostService {

	public function __construct(
		private readonly PostRepository $repository,
		private readonly LikeRepository $likeRepository
	) {}

	public function getFeed(int $userId, ?FeedFiltersDto $filters = null) : ?array {
		if ($userId == null || $userId <= 0)
			return null;

		$posts = $this->repository->getPostsByUser($userId, $filters);

		if ($posts == null || sizeof($posts) == 0)
			return null;

		return $posts;
	}
}