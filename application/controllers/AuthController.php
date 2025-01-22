<?php

namespace Instik\Controllers;

use Instik\Configs\Pages;
use Instik\DTO\AuthLoginDto;
use Instik\DTO\AuthRegisterDto;
use Instik\DTO\AuthChangePasswordDto;
use Instik\Services\AuthService;

use System\Annotations\Routable;
use System\Annotations\Route;
use System\Interfaces\IController;
use System\Security\SessionManager;

#[Routable("/auth")]
class AuthController extends IController {

	public function __construct(
		private readonly AuthService $service,
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

		$isValid = $this->service->validUser($authDto);

		if (!$isValid) {
			$this->loader->load(Pages::login, ["message" => "Usuário inválido"]);
			return;
		}

		$userData = $this->service->getBasicUser($authDto->getEmail());

		if ($userData == null || sizeof($userData) == 0) {
			$this->loader->load(Pages::login, ["message" => "Usuário inválido"]);
			return;
		}

		$this->session->putUser($userData);
		$this->loader->load(Pages::home, $userData);
	}

	#[Route("/register", Route::POST)]
	public function register_init() {
		$registerDto = new AuthRegisterDto($_POST["email"], $_POST["password"], $_POST["password-confirm"]);

		if ($registerDto->getEmail() == "" || $registerDto->getPassword() == "" || $registerDto->getConfirm() == "") {
			$this->loader->load(Pages::login, ["message" => "Preencha os campos corretamente"]);
			return;
		}

		$isValid = $this->service->validRegister($registerDto);

		if (!$isValid) {
			$this->loader->load(Pages::login, ["message" => "Email ou senhas inválidas"]);
			return;
		}

		$user = $this->service->registerUser($registerDto);
		
		if ($user == null || !isset($user['id'])) {
			$this->loader->load(Pages::login, ["message" => "Houve algum erro ao registrar usuário"]);
			return;
		}

		$this->loader->load(Pages::register_confirm, $user);
	}

	#[Route("/send-password-email", Route::POST)]
	public function send_password_email() {
		$email = $_POST['email'];
		
		if ($email == "") {
			$this->loader->load(Pages::login, ["message" => "Preencha os campos corretamente"]);
			return;
		}

		$isValid = $this->service->validEmail($email);

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

		$isValid = $this->service->validHash($changeDto->getHash());

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