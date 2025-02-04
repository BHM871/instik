<?php

namespace Instik\Services;

use Instik\Repository\LikeRepository;
use Instik\Repository\PostRepository;

class LikeService {

	public function __construct(
		private readonly LikeRepository $repository,
		private readonly PostRepository $postRepository
	) {}

	public function postIsLikedByUser(int $postId, int $userId) : bool {
		if ($postId == null || $userId == null)
			return false;

		return $this->repository->postIsLikedByUser($postId, $userId);
	}

	public function likePost(int $postId, int $userId) : bool {
		if ($postId == null || $userId == null)
			return false;

		if ($this->postIsLikedByUser($postId, $userId))
			return false;

		if (!$this->repository->addLike($postId, $userId))
			return false;
		
		$post = $this->postRepository->addLike($postId);

		return $post != null && $post->getId() != null;
	}

	public function unlikePost(int $postId, int $userId) : bool {
		if ($postId == null || $userId == null)
			return false;

		if (!$this->postIsLikedByUser($postId, $userId))
			return false;

		if (!$this->repository->removeLike($postId, $userId))
			return false;
		
		$post = $this->postRepository->removeLike($postId);

		return $post != null && $post->getId() != null;
	}
}