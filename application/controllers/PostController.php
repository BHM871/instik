<?php

use System\Annotations\Request\ResponseBody;
use System\Annotations\Route\Routable;
use System\Annotations\Route\Route;
use System\Annotations\Security\Authenticated;
use System\Interfaces\Application\IController;

#[Routable('/post')]
class PostController extends IController {

	#[Route('/like', Route::POST)]
	#[Authenticated]
	#[ResponseBody]
	public function like() {
		$user = $this->session->getUser();
		if ($user == null || !isset($user['id']) || $user['id'] == null) {
			$this->redirect("/");
			return;	
		}

		return $this->returnJson(['message' => 'Sucesso']);
	}

	#[Route('/unlike', Route::POST, true)]
	#[Authenticated]
	#[ResponseBody]
	public function unlike() {
		$user = $this->session->getUser();
		if ($user == null || !isset($user['id']) || $user['id'] == null) {
			$this->redirect("/");
			return;	
		}

		return $this->returnJson(['message' => 'Sucesso']);
	}

}