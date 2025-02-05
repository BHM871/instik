<?php

namespace Instik\Validators;

use Instik\DTO\AuthLoginDto;
use Instik\Repository\UserRepository;
use Instik\Util\HashGenerator;

use DateTime;
use System\Logger;

class AuthValidator {

	private Logger $logger;

	public function __construct(
		private readonly UserRepository $userRepository
	) {
		$this->logger = new Logger($this);
	}

	public function validToLogin(AuthLoginDto $dto) : bool {
		$user = $this->userRepository->getByEmail($dto->getEmail(), ['password']);

		if ($user == null || $user->getPassword() == null) {
			$user = $this->userRepository->getByUsername($dto->getEmail(), ['password']);
		}

		if ($user == null || $user->getPassword() == null) {
			return false;
		}

		if ($user->getPassword() != HashGenerator::encrypt($dto->getPassword())) {
			return false;
		}

		return true;
	}

	public function validToEmail(string $email) : bool {
		$user = $this->userRepository->getByEmail($email, ['id']);
		
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

			$user = $this->userRepository->getByEmail($email, ['hash_change_password']);

			if ($user == null || $user->getHash() == null)
				return false;

			return $hash == $user->getHash();
		} catch (\Throwable $th) {
			$this->logger->log($th);

			return false;
		}
	}
}