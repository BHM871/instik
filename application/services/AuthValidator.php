<?php

namespace Instik\Services;

use Instik\DTO\AuthLoginDto;
use Instik\DTO\AuthRegisterDto;
use Instik\Repository\AuthRepository;
use Instik\Util\HashGenerator;

class InfosValidator {

	public function __construct(
		private readonly AuthRepository $authRepository
	) {}

	public function validToLogin(AuthLoginDto $dto) : bool {
		$user = $this->authRepository->getUserByEmail($dto->getEmail(), ['password']);

		if ($user == null || $user->getPassword() == null) {
			$user = $this->authRepository->getUserByUsername($dto->getEmail(), ['password']);
		}

		if ($user == null || $user->getPassword() == null) {
			return false;
		}

		if ($user->getPassword() != HashGenerator::encrypt($dto->getPassword())) {
			return false;
		}

		return true;
	}

	public function validToRegister(AuthRegisterDto $dto) : bool {
		$user = $this->authRepository->getUserByEmail($dto->getEmail());

		if ($user != null && $user->getId() != null) {
			return false;
		}

		if (HashGenerator::encrypt($dto->getPassword()) != HashGenerator::encrypt($dto->getConfirm())) {
			return false;
		}

		return true;
	}

	public function validToEmail(string $email) : bool {
		$user = $this->authRepository->getUserByEmail($email, ['id']);
		
		return $user != null && $user->getId() != null;
	}

}