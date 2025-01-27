<?php

namespace Instik\Controllers;

use AuthConfirmRegisterDto;
use Instik\Configs\Navigation;
use Instik\Configs\Pages;
use Instik\DTO\AuthLoginDto;
use Instik\DTO\AuthRegisterDto;
use Instik\DTO\AuthChangePasswordDto;
use Instik\Services\AuthService;
use Instik\Validators\AuthValidator;

use System\Annotations\Route\Routable;
use System\Annotations\Route\Route;
use System\Interfaces\IController;
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
			$this->loader->load(Pages::login, ["message" => "Preencha os campos corretamente"]);
			return;
		}

		$isValid = $this->validator->validToLogin($authDto);

		if (!$isValid) {
			$this->loader->load(Pages::login, ["message" => "Usuário inválido"]);
			return;
		}

		$user = $this->service->getUserToLogin($authDto->getEmail());

		if ($user == null || $user->getId() == null) {
			$this->loader->load(Pages::login, ["message" => "Usuário inválido"]);
			return;
		}

		$this->session->putUser($user);
		$this->redirect(Navigation::feed);
	}

	#[Route("/register", Route::POST)]
	public function register_init() {
		$registerDto = new AuthRegisterDto($_POST["email"], $_POST["password"], $_POST["password-confirm"]);

		if ($registerDto->getEmail() == "" || $registerDto->getPassword() == "" || $registerDto->getConfirm() == "") {
			$this->loader->load(Pages::login, ["message" => "Preencha os campos corretamente"]);
			return;
		}

		$isValid = $this->validator->validToRegister($registerDto);

		if (!$isValid) {
			$this->loader->load(Pages::login, ["message" => "Email ou senhas inválidas"]);
			return;
		}

		$user = $this->service->getInvalidUser($registerDto->getEmail());

		if ($user != null && $user->getId() != null) {
			$this->loader->load(Pages::register_confirm, ["user" => $user->toArray()]);
			return;
		}

		$user = $this->service->registerUser($registerDto);
		
		if ($user == null || $user->getId() == null) {
			$this->loader->load(Pages::login, ["message" => "Houve algum erro ao registrar usuário"]);
			return;
		}

		$this->loader->load(Pages::register_confirm, ['user' => $user->toArray()]);
	}

	#[Route("/confirm-register", Route::POST)]
	public function confirm_register() {
		$confirmDto = new AuthConfirmRegisterDto($_POST["user-id"], $_POST["username"]);
		$profile = $_FILES['profile'];

		if ($confirmDto->getId() == "" || $confirmDto->getUsername() == "") {
			$this->loader->load(Pages::login, ["message" => "Preencha os campos corretamente"]);
			return;
		}

		$isValid = $this->validator->validUserId($confirmDto->getId());

		if (!$isValid) {
			$this->loader->load(Pages::login, ["message" => "Id enviado é inválido"]);
			return;
		}
		
		$user = $this->service->confirmRegister($confirmDto, $profile);

		if ($user == null || $user->getId() == null) {
			$user = $this->service->getInvalidUser($confirmDto->getId());

			$this->loader->load(Pages::register_confirm, [
				"message" => "Erro ao salvar usuário, tente novamente mais tarde", 
				"user" => $user->toArray()
			]);
			return;
		}

		$this->session->putUser($user);
		$this->redirect(Navigation::feed);
	}

	#[Route("/send-password-email", Route::POST)]
	public function send_password_email() {
		$email = $_POST['email'];
		
		if ($email == "") {
			$this->loader->load(Pages::login, ["message" => "Preencha os campos corretamente"]);
			return;
		}

		$isValid = $this->validator->validToEmail($email);

		if (!$isValid) {
			$this->loader->load(Pages::login, ["message" => "Usuário inválido"]);
			return;
		}

		if (!$this->service->sendMailPassword($email)) {
			$this->loader->load(Pages::login, ["message" => "Houve algum erro ao enviar email"]);
			return;
		}	

		$this->loader->load(Pages::login, ["message" => "Email enviado com sucesso"]);
	}

	#[Route("/change-password-view")]
	public function change_password_view() {
		$hash = $_GET['hash'];

		if ($hash == null || $hash == "") {
			$this->loader->load(Pages::login, ["message" => "Troca de senha não permitida. Não foi encontrato o validador"]);
			return;
		}

		$this->loader->load(Pages::change_password, ["hash" => $hash]);
	}

	#[Route("/change-password", Route::POST)]
	public function change_password() {
		$changeDto = new AuthChangePasswordDto($_POST['hash'], $_POST['password'], $_POST['password-confirm']);

		if (
			$changeDto->getHash() == null || $changeDto->getHash() == ""
			|| $changeDto->getPassword() == null || $changeDto->getPassword() == ""
			|| $changeDto->getConfirm() == null || $changeDto->getConfirm() == ""
		) {
			$this->loader->load(Pages::change_password, ["message" => "Algum parâmetro obrigatório está faltando"]);
			return;
		}

		$isValid = $this->validator->validHashToChangePassword($changeDto->getHash());

		if (!$isValid) {
			$this->loader->load(Pages::change_password, ["message" => "Hash enviado não é válido"]);
			return;
		}

		$isSuccess = $this->service->changePassword($changeDto);

		if (!$isSuccess) {
			$this->loader->load(Pages::change_password, ["message" => "Não foi possível trocar a senha"]);
			return;
		}

		$this->loader->load(Pages::login, ["message" => "Senha atualizada com sucesso"]);
	}

}