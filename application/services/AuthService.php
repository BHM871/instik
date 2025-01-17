<?php

class AuthService {

	private AuthModel $model;

	public function __construct(AuthModel $model) {
		$this->model = $model;	
	}

	public function validUser(AuthLoginDto $dto) : bool {
		$password = $this->model->validUserByEmail($dto->getEmail());

		if ($password == null || $password == "") {
			$password = $this->model->validUserByUsername($dto->getEmail());
		}

		if ($password == null || $password == "") {
			return false;
		}

		if ($password != HashGenerator::generate($dto->getPassword())) {
			return false;
		}

		return true;
	}

	public function getBasicUser($email) : object|null {
		$user = $this->model->getBasicUser($email);

		if ($user == null || sizeof($user) == 0) {
			return null;
		}

		return new UserDto($user['id'], $user['email'], $user['username']);
	}

}