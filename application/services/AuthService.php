<?php

namespace Instik\Services;

use Instik\DTO\AuthChangePasswordDto;
use Instik\DTO\AuthRegisterDto;
use Instik\Entity\User;
use Instik\Gateways\EmailFacade;
use Instik\Repository\AuthRepository;
use Instik\Util\HashGenerator;

use System\Logger;

use DateInterval;
use DateTime;

class AuthService {

	private Logger $logger;

	public function __construct(
		private readonly AuthRepository $repository,
		private readonly Notificator $notificator
	) {
		$this->logger = new Logger($this);
	}

	public function getUserByEmail(string $email) : ?User {
		$user = $this->repository->getUserByEmail($email);

		if ($user == null || $user->getId() == null) {
			return null;
		}

		return $user;
	}

	public function validRegister(AuthRegisterDto $dto) : bool {
		$user = $this->repository->getUserByEmail($dto->getEmail());

		if ($user != null && $user->getId() != null) {
			return false;
		}

		if (HashGenerator::encrypt($dto->getPassword()) != HashGenerator::encrypt($dto->getConfirm())) {
			return false;
		}

		return true;
	}

	public function registerUser(AuthRegisterDto $dto) : ?User {
		$user = [
			"email" => $dto->getEmail(),
			"password" => HashGenerator::encrypt($dto->getPassword())
		];

		$user = $this->repository->registerUser(User::instancer($user));

		if ($user == null || $user->getId() == null) {
			return null;
		}

		return $user;
	}

	public function sendMailPassword(string $email) : bool {
		$limit = (new DateTime)
			->add(DateInterval::createFromDateString("15 minutes"))
			->format("Y-m-d H:i:s");

		$hash = HashGenerator::encrypt($email . "|" . $limit);

		if (!$this->repository->savePasswordHash($email, $hash))
			return false;

		return $this->notificator->changePassword($email, "Troca de Senha", $hash);
	}

	public function validHash(string $hash) : bool {
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

	public function changePassword(AuthChangePasswordDto $dto) : bool {
		if ($dto->getPassword() != $dto->getConfirm())
			return false;

		$email = HashGenerator::decrypt($dto->getHash());
		$email = preg_split("/\|/", $email)[0];

		$password = HashGenerator::encrypt($dto->getPassword());
		$user = $this->repository->changePassword($email, $password);

		if ($user == null)
			return false;

		return true;
	}

}