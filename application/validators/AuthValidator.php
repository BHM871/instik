<?php

namespace Instik\Validators;

use DateTime;
use Instik\DTO\AuthLoginDto;
use Instik\DTO\AuthRegisterDto;
use Instik\Repository\AuthRepository;
use Instik\Util\HashGenerator;
use System\Logger;

class AuthValidator {

	private readonly Logger $logger;

	public function __construct(
		private readonly AuthRepository $repository
	) {
		$logger = new Logger($this);
	}

	public function validToLogin(AuthLoginDto $dto) : bool {
		$user = $this->repository->getUserByEmail($dto->getEmail(), ['password']);

		if ($user == null || $user->getPassword() == null) {
			$user = $this->repository->getUserByUsername($dto->getEmail(), ['password']);
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
		$user = $this->repository->getUserByEmail($dto->getEmail());

		if ($user != null && $user->getId() != null) {
			return false;
		}

		if (HashGenerator::encrypt($dto->getPassword()) != HashGenerator::encrypt($dto->getConfirm())) {
			return false;
		}

		return true;
	}

	public function validToEmail(string $email) : bool {
		$user = $this->repository->getUserByEmail($email, ['id']);
		
		return $user != null && $user->getId() != null;
	}

	public function validHashToChangePassword(string $hash) : bool {
		$content = HashGenerator::decrypt($hash);
		$content = preg_split("/\|/", $content);
		
		try {
			if (sizeof($content) < 2)
				return false;

			$email = $content[0];
			$limit = $content[1];

			$limit = new DateTime($limit);

			if ((new DateTime()) > $limit)
				return false;

			$user = $this->repository->getUserByEmail($email, ['hash_change_password']);

			if ($user == null || $user->getHash() == null)
				return false;

			return $hash == $user->getHash();
		} catch (\Throwable $th) {
			$this->logger->log($th);

			return false;
		}
	}

}