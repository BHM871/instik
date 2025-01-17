<?php

require_once("./system/interfaces/IController.php");
require_once("./system/annotations/Routable.php");
require_once("./system/annotations/Route.php");

#[Routable("/auth")]
class AuthController extends IController {

	private AuthService $service;

	public function __construct(AuthService $service) {
		parent::__construct();
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


		$this->loader->load(Pages::home, get_object_vars($userData));
	}

	#[Route("/register", Route::POST)]
	public function register_init() {
		$email = $_POST["email"];
		$password = $_POST["password"];
		$confirmation = $_POST["password-confirm"];

		echo "$email ";
		echo "$password ";
		echo "$confirmation ";
	}

}