<?php

namespace Instik\Services;

use Instik\Repository\CommentRepository;

class CommentService {

	public function __construct(
		private readonly CommentRepository $repository
	) {}

	public function commentPost(int $userId, int $postId, string $comment) : bool {
		if ($userId == null || $postId == null || $comment == null)
			return false;
		
		
		return $this->repository->addComment($userId, $postId, $comment);	
	}

}