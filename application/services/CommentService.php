<?php

namespace Instik\Services;

use Instik\Entity\Comment;
use Instik\Repository\CommentRepository;

class CommentService {

	public function __construct(
		private readonly CommentRepository $repository
	) {}

	public function commentPost(int $userId, int $postId, string $comment) : ?Comment {
		if ($userId == null || $postId == null || $comment == null)
			return null;
		
		
		return $this->repository->addComment($userId, $postId, $comment);	
	}

}