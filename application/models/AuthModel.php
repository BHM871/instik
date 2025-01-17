<?php

require_once("./system/interfaces/IModel.php");

class AuthModel extends IModel {

	public function validUserByEmail($email) : string|null {
		if ($email == null || $email == "") {
			return null;
		}

		$result = $this->db->get('user', ['password'], ['email' => $email]);

		if ($result == null || sizeof($result) == 0) {
			return null;
		}

		if ($result[0]['password'] == null || $result[0]['password'] == "") {
			return null;
		}

		return $result[0]['password'];
	}	

	public function validUserByUsername($username) : string|null {
		if ($username == null || $username == "") {
			return null;
		}

		$result = $this->db->get('user', ['password'], ['username' => $username]);

		if ($result == null || sizeof($result) == 0) {
			return null;
		}

		if ($result['password'] == null || $result['password'] == "") {
			return null;
		}

		return $result['password'];
	}	

	public function getBasicUser($email) : array|null {
		if ($email == null || $email == "") {
			return null;
		}
		
		$result = $this->db->query("SELECT id, email, username FROM user WHERE email = ? OR username = ?", [$email, $email]);
		
		if ($result == null || sizeof($result) == 0) {
			return null;
		}

		return $result[0];
	}

}