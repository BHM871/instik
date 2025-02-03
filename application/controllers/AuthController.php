<?php

namespace Instik\Controllers;

use Instik\Configs\Navigation;
use Instik\Configs\Pages;
use Instik\DTO\AuthLoginDto;
use Instik\DTO\AuthRegisterDto;
use Instik\DTO\AuthChangePasswordDto;
use Instik\DTO\AuthConfirmRegisterDto;
use Instik\DTO\Entity\UserDto;
use Instik\Services\AuthService;
use Instik\Validators\AuthValidator;

use System\Annotations\Route\Routable;
use System\Annotations\Route\Route;
use System\Interfaces\Application\IController;
use System\Security\SessionManager;

#[Routable('/auth')]
class AuthController extends IController {

	public function __construct(
		private readonly AuthService $service,
		private readonly AuthValidator $validator,
		SessionManager $session
	) {
		parent::__construct($session);
	}

	#[Route("/authenticate", Route::POST)]
	public function authenticate() {
		$authDto = new AuthLoginDto($_POST['email'], $_POST['password']);
		
		if ($authDto->getEmail() == "" || $authDto->getPassword() == "") {
			return $this->returnPage(Pages::login, ["message" => "Preencha os campos corretamente"]);
		}

		$isValid = $this->validator->validToLogin($authDto);

		if (!$isValid) {
			return $this->returnPage(Pages::login, ["message" => "Usuário inválido"]);
		}

		$user = $this->service->getUserToLogin($authDto->getEmail());

		if ($user == null || $user->getId() == null) {
			return $this->returnPage(Pages::login, ["message" => "Usuário inválido"]);
		}

		$this->session->putUser(UserDto::by($user));
		$this->redirect(Navigation::feed);
	}

	#[Route("/register", Route::POST)]
	public function register_init() {
		$registerDto = new AuthRegisterDto($_POST["email"], $_POST["password"], $_POST["password-confirm"]);

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
		$confirmDto = new AuthConfirmRegisterDto($_POST["user-id"], $_POST["username"]);
		$profile = $_FILES['profile'];

		if ($this->session->isAuthenticated() && $this->session->getUser()['id'] == $confirmDto->getId()) {
			$this->redirect(Navigation::feed);
			return;
		}

		if ($confirmDto->getId() == "" || $confirmDto->getUsername() == "") {
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

	#[Route("/send-password-email", Route::POST)]
	public function send_password_email() {
		$email = $_POST['email'];
		
		if ($email == "") {
			return $this->returnPage(Pages::login, ["message" => "Preencha os campos corretamente"]);
		}

		$isValid = $this->validator->validToEmail($email);

		if (!$isValid) {
			return $this->returnPage(Pages::login, ["message" => "Usuário inválido"]);
		}

		if (!$this->service->sendMailPassword($email)) {
			return $this->returnPage(Pages::login, ["message" => "Houve algum erro ao enviar email"]);
		}	

		if ($this->session->isAuthenticated())
			$this->redirect(Navigation::feed);
		else
			return $this->returnPage(Pages::login, ["message" => "Email enviado com sucesso"]);
	}

	#[Route("/change-password-view")]
	public function change_password_view() {
		$hash = $_GET['hash'];

		if ($hash == null || $hash == "") {
			return $this->returnPage(Pages::login, ["message" => "Troca de senha não permitida. Não foi encontrato o validador"]);
		}

		return $this->returnPage(Pages::change_password, ["hash" => $hash]);
	}

	#[Route("/change-password", Route::POST)]
	public function change_password() {
		$changeDto = new AuthChangePasswordDto($_POST['hash'], $_POST['password'], $_POST['password-confirm']);

		if (
			$changeDto->getHash() == null || $changeDto->getHash() == ""
			|| $changeDto->getPassword() == null || $changeDto->getPassword() == ""
			|| $changeDto->getConfirm() == null || $changeDto->getConfirm() == ""
		) {
			return $this->returnPage(Pages::change_password, ["message" => "Algum parâmetro obrigatório está faltando"]);
		}

		$isValid = $this->validator->validHashToChangePassword($changeDto->getHash());

		if (!$isValid) {
			return $this->returnPage(Pages::change_password, ["message" => "Hash enviado não é válido"]);
		}

		$isSuccess = $this->service->changePassword($changeDto);

		if (!$isSuccess) {
			return $this->returnPage(Pages::change_password, ["message" => "Não foi possível trocar a senha"]);
		}

		return $this->returnPage(Pages::login, ["message" => "Senha atualizada com sucesso"]);
	}

	#[Route("/logout", [Route::GET, Route::POST])]
	public function logout() {
		$this->session->removeUser();

		$this->redirect("/");
	}

}