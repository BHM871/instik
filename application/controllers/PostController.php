<?php

use Instik\Services\PostService;

use System\Annotations\Request\ResponseBody;
use System\Annotations\Route\Routable;
use System\Annotations\Route\Route;
use System\Annotations\Security\Authenticated;
use System\Interfaces\Application\IController;
use System\Security\SessionManager;

#[Routable('/post')]
class PostController extends IController {

	public function __construct(
		private readonly PostService $service,
		SessionManager $session
	) {
		parent::__construct($session);
	}

	#[Route('/like', Route::POST)]
	#[Authenticated]
	#[ResponseBody]
	public function like() {
		$user = $this->session->getUser();
		if ($user == null || !isset($user['id']) || $user['id'] == null) {
			return "Usuário não autenticado, faça login";
		}

		$postId = $_POST['postId'];

		if ($postId == null || $postId == '') return "ID do post não informado";

		$postId = (int) $postId;

		$success = $this->service->likePost($postId, $user['id']);

		echo var_dump($success);

		return $this->returnJson(['success' => $success]);
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