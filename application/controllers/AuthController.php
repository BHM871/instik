<?php

namespace Instik\Controllers;

use Instik\Configs\Navigation;
use Instik\Configs\Pages;
use Instik\DTO\AuthLoginDto;
use Instik\DTO\AuthChangePasswordDto;
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