<?php

namespace Instik\Repository;

use Instik\Entity\User;

use System\Interfaces\IRepository;

class AuthRepository extends IRepository {

	public function getUserByEmail(string $email, array $infos = ["*"]) : ?User {
		if ($email == null || $email == "") {
			return null;
		}

		$result = $this->db->get('user', $infos, ['email' => $email, 'is_valid' => 1]);

		if ($result == null || sizeof($result) == 0) {
			return null;
		}

		return User::instancer($result[0]);
	}

	public function getUserByUsername(string $username, array $infos = ["*"]) : ?User {
		if ($username == null || $username == "") {
			return null;
		}

		$result = $this->db->get('user', $infos, ['username' => $username, 'is_valid' => 1]);

		if ($result == null || sizeof($result) == 0) {
			return null;
		}

		return User::instancer($result[0]);
	}

	public function registerUser(User $user) : ?User {
		if ($user == null || !is_object($user) || !($user instanceof User)) {
			return null;
		}
		
		$result = $this->db->insert('user', $user->toArray());

		if ($result == null || sizeof($result) == 0)
			return null;

		return User::instancer($result[0]);
	}

	public function savePasswordHash(string $email, string $hash) : bool {
		$result = $this->db->update('user', ['email' => $email, 'hash_change_password' => $hash]);

		return $result != null;
	}

	public function changePassword(string $email, string $password) : ?User {
		$result = $this->db->update('user', [
			'email' => $email, 
			'password' => $password, 
			'hash_change_password' => null
		]);

		if ($result == null || sizeof($result) == 0)
			return null;

		return User::instancer($result[0]);
	}

}