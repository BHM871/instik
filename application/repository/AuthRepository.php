<?php

namespace Instik\Repository;

use Instik\Entity\User;

use System\Interfaces\Application\IRepository;

class AuthRepository extends IRepository {
	
	public function getUserById(int $id, array $infos = ["*"]) : ?User {
		if ($id == null || $id <= 0) {
			return null;
		}

		$result = $this->db->get('user', $infos, ['id' => $id, 'is_valid' => 1]);

		if ($result == null || sizeof($result) == 0) {
			return null;
		}

		return User::instancer($result[0]);
	}

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

	public function getInvalidUserById(int $id) : ?User {
		if ($id == null || $id <= 0) {
			return null;
		}

		$result = $this->db->get('user', ['id', 'email', 'username'], ['id' => $id]);

		if ($result == null || sizeof($result) == 0) {
			return null;
		}

		return User::instancer($result[0]);
	}

	public function getInvalidUserByEmail(string $email) : ?User {
		if ($email == null || $email == "") {
			return null;
		}

		$result = $this->db->get('user', ['id', 'email', 'username'], ['email' => $email]);

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

	public function confirmRegister(User $user) : ?User {
		if ($user == null || !is_object($user) || !($user instanceof User)) {
			return null;
		}
		
		$result = $this->db->update('user', [
			'is_valid' => true, 
			'username' => $user->getUsername()
		], [
			'id' => $user->getId()
		]);

		if ($result == null || sizeof($result) == 0)
			return null;

		return User::instancer($result[0]);
	}

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