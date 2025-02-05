<?php

namespace Instik\Validators;

use Instik\DTO\UserRegisterDto;
use Instik\Repository\UserRepository;
use Instik\Util\HashGenerator;

class UserValidator {

	public function __construct(
		private readonly UserRepository $repository
	) {}

	public function validToRegister(UserRegisterDto $dto) : bool {
		$user = $this->repository->getByEmail($dto->getEmail());

		if ($user != null && $user->getId() != null) {
			return false;
		}

		if (HashGenerator::encrypt($dto->getPassword()) != HashGenerator::encrypt($dto->getConfirm())) {
			return false;
		}

		return true;
	}

	public function validUserId(int $id) : bool {
		if ($id == null || $id <= 0)
			return false;

		$user = $this->repository->getById($id, ['id']);

		if ($user == null || $user->getId() == null)
			$user = $this->repository->getInvalidUserById($id, ['id']);

		if ($user == null || $user->getId() == null)
			return false;

		return true;
	}

}