<?php

require_once("./system/interfaces/IModel.php");

class AuthModel extends IModel {

	public function validUserByEmail($email) : ?string {
		if ($email == null || $email == "") {
			return null;
		}

		$result = $this->db->get('user', ['password'], ['email' => $email, 'is_valid' => 1]);

		if ($result == null || sizeof($result) == 0) {
			return null;
		}

		if ($result[0]['password'] == null || $result[0]['password'] == "") {
			return null;
		}

		return $result[0]['password'];
	}	

	public function validUserByUsername($username) : ?string {
		if ($username == null || $username == "") {
			return null;
		}

		$result = $this->db->get('user', ['password'], ['username' => $username, 'is_valid' => 1]);

		if ($result == null || sizeof($result) == 0) {
			return null;
		}

		if ($result[0]['password'] == null || $result[0]['password'] == "") {
			return null;
		}

		return $result[0]['password'];
	}	

	public function getBasicUser($email) : ?array {
		if ($email == null || $email == "") {
			return null;
		}
		
		$result = $this->db->query("SELECT id, email, username FROM user WHERE is_valid = 1 AND (email = ? OR username = ?)", [$email, $email]);
		
		if ($result == null || sizeof($result) == 0) {
			return null;
		}

		return $result[0];
	}

	public function validEmailFree(string $email) : bool {
		if ($email == null || $email == "") {
			return false;
		}

		$result = $this->db->get('user', ['id'], ['email' => $email]);

		if ($result == null || sizeof($result) == 0) {
			return true;
		}

		return false;
	}

	public function registerUser(array $user) : ?array {
		if ($user == null || !is_array($user) || sizeof($user) == 0) {
			return null;
		}
		
		$result = $this->db->insert('user', $user);

		if ($result == null || sizeof($result) == 0)
			return null;

		return $result[0];
	}

	public function savePasswordHash(string $email, string $hash) : bool {
		$result = $this->db->update('user', ['email' => $email, 'hash_change_password' => $hash]);

		return $result != null;
	}

	public function getHashByEmail(string $email) : ?string {
		$result = $this->db->get('user', ['hash_change_password'], ['email' => $email]);

		if ($result == null || sizeof($result) == 0)
			return null;

		if (!isset($result[0]['hash_change_password']))
			return null;

		return $result[0]['hash_change_password'];
	}

	public function changePassword(string $email, string $password) : ?array {
		$result = $this->db->update('user', [
			'email' => $email, 
			'password' => $password, 
			'hash_change_password' => null
		]);

		if ($result == null || sizeof($result) == 0)
			return null;

		return $result;
	}

}