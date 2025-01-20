<?php

require_once("./application/gateways/EmailFacade.php");

class AuthService {

	private AuthModel $model;

	private EmailFacade $mailSender;

	public function __construct(AuthModel $model, EmailFacade $emailFacade) {
		$this->model = $model;
		$this->mailSender = $emailFacade;
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

	public function validEmail(string $email) : bool {
		$password = $this->model->validUserByEmail($email);
		
		return $password != null && $password != "";
	}

	public function sendMailPassword(string $email) : bool {
		$limit = (new DateTime)
			->add(DateInterval::createFromDateString("15 minutes"))
			->format("Y-m-d+H:i:s");

		$hash = HashGenerator::encrypt($email . "|" . $limit);

		if (!$this->model->savePasswordHash($email, $hash))
			return false;

		return $this->mailSender->sendEmail([$email], "Troca de Senha", EmailTemplate::changePassword($hash));	
	}

}