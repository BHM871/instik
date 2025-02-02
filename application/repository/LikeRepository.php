<?php

namespace Instik\Repository;

use System\Interfaces\Application\IRepository;

class LikeRepository extends IRepository {

	public function postIsLikedByUser(int $postId, int $userId) : bool {
		if ($postId == null || $userId == null)
			return false;

		$result = $this->db->get('like', ['id'], ['id_post' => $postId, 'id_liker' => $userId]);
		
		if ($result == null || empty($result))
			return false;

		return true;
	}

	public function addLike(int $postId, int $userId) : bool {
		if ($postId == null || $userId == null)
			return false;

		$result = $this->db->insert('like', ['id_post' => $postId, 'id_liker' => $userId]);
		
		if ($result == null || empty($result))
			return false;

		return true;
	}

}