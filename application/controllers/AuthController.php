<?php

include("./system/interfaces/IController.php");
include("./system/annotations/Routable.php");
include("./system/annotations/Route.php");

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