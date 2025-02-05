<?php

namespace Instik\Services;

use Instik\DTO\FeedFiltersDto;
use Instik\Entity\Post;
use Instik\Repository\CommentRepository;
use Instik\Repository\LikeRepository;
use Instik\Repository\PostRepository;
use Instik\Repository\UserRepository;

class PostService {

	public function __construct(
		private readonly PostRepository $repository,
		private readonly LikeRepository $likeRepository,
		private readonly UserRepository $userRepository,
		private readonly CommentRepository $commentRepository
	) {}

	public function getFeed(int $userId, ?FeedFiltersDto $filters = null) : ?array {
		if ($userId == null || $userId <= 0)
			return null;

		$posts = $this->repository->getPostsByUser($userId, $filters);

		if ($posts == null || empty($posts))
			return null;

		$pts = $posts;
		$posts = [];
		foreach ($pts as $post) {
			$post = $post->toArray();

			$user = $this->userRepository->getById($post['publisher']['id']);
			if ($user != null) {
				$post['publisher'] = $user->toArray();
			}

			$comments = $this->commentRepository->getByPostId($post['id']);
			if ($comments != null) {
				$commentsArr = [];
				foreach ($comments as $comment) {
					$user = $this->userRepository->getById($comment->getUser()->getId());

					$comment = $comment->toArray();
					$comment['user'] = $user->toArray();
					
					$commentsArr[] = $comment;
				}

				$post['comments'] = $commentsArr;
			}

			$posts[] = Post::instancer($post);
		}

		return $posts;
	}
}