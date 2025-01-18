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

		if ($password != HashGenerator::encrypt($dto->getPassword())) {
			return false;
		}

		return true;
	}

	public function getBasicUser($email) : ?array {
		$user = $this->model->getBasicUser($email);

		if ($user == null || sizeof($user) == 0) {
			return null;
		}

		return $user;
	}

	public function validRegister(AuthRegisterDto $dto) : bool {
		$isValid = $this->model->validEmailFree($dto->getEmail());

		if (!$isValid) {
			return false;
		}

		if (HashGenerator::encrypt($dto->getPassword()) != HashGenerator::encrypt($dto->getConfirm())) {
			return false;
		}

		return true;
	}

	public function registerUser(AuthRegisterDto $dto) : ?array {
		$user = [
			"email" => $dto->getEmail(),
			"password" => HashGenerator::encrypt($dto->getPassword())
		];

		$user = $this->model->registerUser($user);

		if ($user == null || sizeof($user) == 0) {
			return null;
		}

		return $user;
	}

}