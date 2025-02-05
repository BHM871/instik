<?php

namespace Instik\Services;

use Instik\DTO\UserConfirmRegisterDto;
use Instik\DTO\UserRegisterDto;
use Instik\Entity\User;
use Instik\Repository\UserRepository;
use Instik\Services\FileService;
use Instik\Util\HashGenerator;

class UserService {

	public function __construct(
		private readonly UserRepository $repository,
		private readonly FileService $fileService,
		private readonly Notificator $notificator
	) {}

	public function getUser(string|int $identificator) : ?User {
		$user = $this->repository->getById($identificator);

		if ($user == null || $user->getId() == null) {
			$user = $this->repository->getByEmail($identificator);
		}

		if ($user == null || $user->getId() == null) {
			$user = $this->repository->getByUsername($identificator);
		}

		if ($user == null || $user->getId() == null) {
			return null;
		}

		return $user;
	}

	public function registerUser(UserRegisterDto $dto) : ?User {
		$user = [
			"email" => $dto->getEmail(),
			"password" => HashGenerator::encrypt($dto->getPassword())
		];

		$user = $this->repository->registerUser(User::instancer($user));

		if ($user == null || $user->getId() == null) {
			return null;
		}

		$this->notificator->confirmRegister(($dto->getEmail()));

		return $user;
	}

	public function confirmRegister(UserConfirmRegisterDto $dto, array $profile = null) : ?User {
		$imagePath = null;
		if ($profile != null && is_array($profile) && isset($profile['type']) && preg_match("/image/", $profile['type'])) {
			$filename = $dto->getId() . "_profile_image";
			$filename .= "." . preg_split("/\//", $profile['type'])[1];

			$imagePath = $this->fileService->upload($profile, $filename, DEFAULT_UPLOADS_PATH . "/profile");
		}

		$user = new User(id: $dto->getId(), username: $dto->getUsername(), image_path: $imagePath);
		$user = $this->repository->confirmRegister($user);

		if ($user == null || $user->getId() == null)
			return null;

		return $user;
	}

	public function getInvalidUser(string $identificator) : ?User {
		$user = $this->repository->getInvalidUserByEmail($identificator);

		if ($user == null || $user->getId() == null) {
			return null;
		}

		return $user;
	}

}