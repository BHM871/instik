<?php

namespace Instik\Repository;

use System\Interfaces\Application\IRepository;
use Throwable;

class CommentRepository extends IRepository {

	public function addComment(int $userId, int $postId, string $comment) : bool {
		try {
			$result = $this->db->insert('comment', ['id_commenter' => $userId, 'id_post' => $postId, 'content' => $comment]);

			return $result != null;
		} catch (Throwable $th) {
			echo var_dump($th);
			return false;
		}
	}

}