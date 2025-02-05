<?php

namespace Instik\Validators;

use Instik\Repository\PostRepository;

use Throwable;

class PostValidator {

	public function __construct(
		private readonly PostRepository $repository
	) {}

	public function validLikeAndUnlike(?string $postId) : bool {
		if ($postId == null || trim($postId) == '')
			return false;

		try {
			if (!is_int((int) $postId))
				return false;

			$post = $this->repository->getById($postId);
			return $post != null && $post->getId() != null;
		} catch (Throwable $th) {
			return false;
		}
	}

	public function validComment(?string $postId, ?string $comment) : bool {
		if ($postId == null || trim($postId) == '')
			return false;

		try {
			if (!is_int((int) $postId))
				return false;

			if ($comment == null || trim($comment) == '')
				return false;

			$post = $this->repository->getById($postId);
			return $post != null && $post->getId() != null;
		} catch (Throwable $th) {
			return false;
		}
	}

}