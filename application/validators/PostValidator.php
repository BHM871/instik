<?php

namespace Instik\Validators;

use Throwable;

class PostValidator {

	public function validLikeAndUnlike(?string $postId) : bool {
		if ($postId == null || trim($postId) == '')
			return false;

		try {
			return is_int((int) $postId);	
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

			return $comment != null && trim($comment) != '';
		} catch (Throwable $th) {
			return false;
		}
	}

}