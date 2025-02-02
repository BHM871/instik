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

	public function postIsLikedByUser(int $postId, int $userId) : bool {
		if ($postId == null || $userId == null)
			return false;

		return $this->likeRepository->postIsLikedByUser($postId, $userId);
	}

	public function likePost(int $postId, int $userId) : bool {
		if ($postId == null || $userId == null)
			return false;

		if ($this->postIsLikedByUser($postId, $userId))
			return false;

		if (!$this->likeRepository->addLike($postId, $userId))
			return false;
		
		$post = $this->repository->addLike($postId);

		return $post != null && $post->getId() != null;
	}
}