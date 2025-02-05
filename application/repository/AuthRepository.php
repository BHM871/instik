<?php

namespace Instik\Repository;

use Instik\Entity\User;

use System\Interfaces\Application\IRepository;

class AuthRepository extends IRepository {
	

	public function savePasswordHash(string $email, string $hash) : bool {
		$result = $this->db->update('user', ['hash_change_password' => $hash], ['email' => $email]);

		return $result != null;
	}

	public function changePassword(string $email, string $password) : ?User {
		$result = $this->db->update('user', [
			'password' => $password, 
			'hash_change_password' => null
		], [
			'email' => $email, 
		]);

		if ($result == null || sizeof($result) == 0)
			return null;

		return User::instancer($result[0]);
	}

}