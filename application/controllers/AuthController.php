<?php

require_once("./system/interfaces/IController.php");
require_once("./system/annotations/Routable.php");
require_once("./system/annotations/Route.php");

#[Routable("/auth")]
class AuthController extends IController {

	private SessionManager $session;
	private AuthService $service;

	public function __construct(SessionManager $session, AuthService $service) {
		parent::__construct();
		$this->session = $session;
		$this->service = $service;
	}

	#[Route("/authenticate", Route::POST)]
	public function login() {
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

}