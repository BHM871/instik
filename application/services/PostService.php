<?php

namespace Instik\Services;

use Instik\DTO\FeedFiltersDto;
use Instik\Entity\Post;
use Instik\Entity\User;
use Instik\Repository\CommentRepository;
use Instik\Repository\LikeRepository;
use Instik\Repository\PostRepository;
use Instik\Repository\UserRepository;

class PostService {

	public function __construct(
		private readonly FileService $fileService,
		private readonly PostRepository $repository,
		private readonly LikeRepository $likeRepository,
		private readonly UserRepository $userRepository,
		private readonly CommentRepository $commentRepository
	) {}

	public function getFeed(int $userId, ?FeedFiltersDto $filters = null) : ?array {
		if ($userId == null || $userId <= 0)
			return null;

		$posts = $this->repository->getPostsToFeed($userId, $filters);

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

	public function publish(int $userId, ?string $caption, ?array $image) : ?Post {
		$post = new Post(publisher: new User(id: $userId), caption: $caption);
		
		$post = $this->repository->publish($post);
		if ($post == null || $post->getId() == null)
			return null;

		if ($image != null) {
			$filename = $post->getId() . "_post_image";
			$filename .= "." . preg_split("/\//", $image['type'])[1];

			$imagePath = $this->fileService->upload($image, $filename, DEFAULT_UPLOADS_PATH . "/posts");

			$post = $post->toArray();
			$post['image_path'] = $imagePath;
			$post = $this->repository->update(Post::instancer($post));
		}

		if ($post == null || $post->getId() == null)
			return null;
		
		return $post;
	}
}