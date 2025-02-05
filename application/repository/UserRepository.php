<?php

namespace Instik\Repository;

use Instik\Entity\User;
use System\Interfaces\Application\IRepository;

class UserRepository extends IRepository {

	public function getById(int $id) : ?User {
		$result = $this->db->get('user', ['*'], ['id' => $id]);
		
		if ($result == null || empty($result))
			return null;

		return User::instancer($result[0]);
	}

}