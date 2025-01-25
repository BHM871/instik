<?php

namespace Instik\Services;

use AuthConfirmRegisterDto;
use Instik\DTO\AuthChangePasswordDto;
use Instik\DTO\AuthRegisterDto;
use Instik\Entity\User;
use Instik\Repository\AuthRepository;
use Instik\Util\HashGenerator;

use System\Logger;

use DateInterval;
use DateTime;
use FileService;

class AuthService {

	private Logger $logger;

	public function __construct(
		private readonly AuthRepository $repository,
		private readonly Notificator $notificator,
		private readonly FileService $fileService
	) {
		$this->logger = new Logger($this);
	}

	public function getUserToLogin(string $emailOrUsername) : ?User {
		$user = $this->repository->getUserByEmail($emailOrUsername);

		if ($user == null || $user->getId() == null) {
			$user = $this->repository->getUserByUsername($emailOrUsername);
		}

		if ($user == null || $user->getId() == null) {
			return null;
		}

		return $user;
	}

	public function getUser(string|int $identificator) : ?User {
		$user = $this->repository->getUserById($identificator);

		if ($user == null || $user->getId() == null) {
			$user = $this->repository->getUserByEmail($identificator);
		}

		if ($user == null || $user->getId() == null) {
			$user = $this->repository->getUserByUsername($identificator);
		}

		if ($user == null || $user->getId() == null) {
			return null;
		}

		return $user;
	}

	public function getInvalidUser(string $identificator) : ?User {
		$user = $this->repository->getInvalidUserByEmail($identificator);

		if ($user == null || $user->getId() == null) {
			return null;
		}

		return $user;
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

	public function confirmRegister(AuthConfirmRegisterDto $dto, array $profile = null) : ?User {
		$imagePath = null;
		if ($profile != null && is_array($profile) && isset($profile['type']) && preg_match("/image/", $profile['type'])) {
			$filename = $dto->getId() . "_profile_image";
			$filename .= "." . preg_split("/\//", $profile['type'])[1];

			$imagePath = $this->fileService->upload($profile, $filename, DEFAULT_UPLOADS_PATH . "/profile");
		}

		$user = new User($dto->getId(), $dto->getUsername(), null, null, true, $imagePath);
		$user = $this->repository->confirmRegister($user);

		if ($user == null || $user->getId() == null)
			return null;

		return $user;
	}

	public function sendMailPassword(string $email) : bool {
		$limit = (new DateTime)
			->add(DateInterval::createFromDateString("15 minutes"))
			->format("Y-m-d H:i:s");

		$hash = HashGenerator::encrypt($email . "|" . $limit);

		if (!$this->repository->savePasswordHash($email, $hash))
			return false;

		return $this->notificator->changePassword($email, $hash);
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