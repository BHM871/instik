<?php

namespace Instik\Controllers;

use Instik\Configs\Navigation;
use Instik\Configs\Pages;
use Instik\DTO\Entity\UserDto;
use Instik\DTO\UserConfirmRegisterDto;
use Instik\DTO\UserRegisterDto;
use Instik\Services\UserService;
use Instik\Validators\UserValidator;

use System\Annotations\Route\Routable;
use System\Annotations\Route\Route;
use System\Interfaces\Application\IController;
use System\Security\SessionManager;

#[Routable("/user")]
class UserController extends IController {

	public function __construct(
		private readonly UserService $service,
		private readonly UserValidator $validator,
		SessionManager $session
	) {
		parent::__construct($session);
	}

	#[Route("/register", [Route::GET ,Route::POST])]
	public function register_init() {
		$registerDto = null;
		
		if (isset($_POST['email']))
			$registerDto = new UserRegisterDto($_POST["email"], $_POST["password"], $_POST["password-confirm"]);
		else if (isset($_GET['email']))
			$registerDto = new UserRegisterDto($_GET["email"], $_GET["password"], $_GET["password-confirm"]);

		if ($registerDto == null)
			return $this->returnPage(Pages::login, ["message" => "Campos não foram enviador corretamente"]);

		if ($this->session->isAuthenticated() && $this->session->getUser()['email'] == $registerDto->getEmail()) {
			$this->redirect(Navigation::feed);
			return;
		}

		if ($registerDto->getEmail() == "" || $registerDto->getPassword() == "" || $registerDto->getConfirm() == "") {
			return $this->returnPage(Pages::login, ["message" => "Preencha os campos corretamente"]);
		}

		$isValid = $this->validator->validToRegister($registerDto);

		if (!$isValid) {
			return $this->returnPage(Pages::login, ["message" => "Email ou senhas inválidas"]);
		}

		$user = $this->service->getInvalidUser($registerDto->getEmail());

		if ($user != null && $user->getId() != null) {
			return $this->returnPage(Pages::register_confirm, ["user" => ['id' => $user->getId(), 'email' => $user->getEmail()]]);
		}

		$user = $this->service->registerUser($registerDto);
		
		if ($user == null || $user->getId() == null) {
			return $this->returnPage(Pages::login, ["message" => "Houve algum erro ao registrar usuário"]);
		}

		return $this->returnPage(Pages::register_confirm, ['user' => ['id' => $user->getId(), 'email' => $user->getEmail()]]);
	}

	#[Route("/confirm-register", Route::POST)]
	public function confirm_register() {
		$confirmDto = new UserConfirmRegisterDto($_POST["user-id"], $_POST["username"]);
		$profile = $_FILES['profile'];

		if ($this->session->isAuthenticated() && $this->session->getUser()['id'] == $confirmDto->getId()) {
			$this->redirect(Navigation::feed);
			return;
		}

		if ($confirmDto->getId() == "" || $confirmDto->getUsername() == "" || $profile['size'] <= 0) {
			return $this->returnPage(Pages::login, ["message" => "Preencha os campos corretamente"]);
		}

		$isValid = $this->validator->validUserId($confirmDto->getId());

		if (!$isValid) {
			return $this->returnPage(Pages::login, ["message" => "Id enviado é inválido"]);
		}
		
		$user = $this->service->confirmRegister($confirmDto, $profile);

		if ($user == null || $user->getId() == null) {
			$user = $this->service->getInvalidUser($confirmDto->getId());

			return $this->returnPage(Pages::register_confirm, [
				"message" => "Erro ao salvar usuário, tente novamente mais tarde", 
				"user" => $user->toArray()
			]);
		}

		$this->session->putUser(UserDto::by($user));
		$this->redirect(Navigation::feed);
	}

}