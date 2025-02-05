<?php

namespace Instik\Repository;

use Instik\Entity\Comment;
use System\Interfaces\Application\IRepository;
use Throwable;

class CommentRepository extends IRepository {

	public function addComment(int $userId, int $postId, string $comment) : ?Comment {
		try {
			$result = $this->db->insert('comment', ['id_commenter' => $userId, 'id_post' => $postId, 'content' => $comment]);

			if ($result == null || empty($result))
				return null;

			return Comment::instancer($result[0]);
		} catch (Throwable $th) {
			echo var_dump($th);
			return false;
		}
	}

}