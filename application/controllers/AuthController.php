<?php

require_once("./system/interfaces/IController.php");
require_once("./system/annotations/Routable.php");
require_once("./system/annotations/Route.php");

#[Routable("/auth")]
class AuthController extends IController {

	#[Route("/authenticate", Route::POST)]
	public function login() {
		$email = $_POST["email"];
		$password = $_POST["password"];

		echo "$email ";
		echo "$password ";
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