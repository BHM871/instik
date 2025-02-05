<?php

use Instik\Configs\Navigation;
use Instik\Configs\Pages;
use Instik\DTO\Entity\CommentDto;
use Instik\Services\CommentService;
use Instik\Services\LikeService;
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
		private readonly LikeService $likeService,
		private readonly CommentService $commentService,
		SessionManager $session
	) {
		parent::__construct($session);
	}
	
	#[Route('/post', Route::GET)]
	#[Authenticated]
	public function post() {
		$user = $this->session->getUser();
		if ($user == null || !isset($user['id']) || $user['id'] == null) {
			$this->redirect("/");
			return;
		}

		return $this->returnPage(Pages::add_post, ['user' => $user]);
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

		$success = $this->likeService->likePost($postId, $user['id']);

		return $this->returnJson(['success' => $success]);
	}

	#[Route('/unlike', Route::POST)]
	#[Authenticated]
	#[ResponseBody]
	public function unlike() {
		$user = $this->session->getUser();
		if ($user == null || !isset($user['id']) || $user['id'] == null) {
			return "Usuário não autenticado, faça login";
		}

		$postId = $_POST['postId'];

		if ($postId == null || $postId == '') return "ID do post não informado";

		$postId = (int) $postId;

		$success = $this->likeService->unlikePost($postId, $user['id']);

		return $this->returnJson(['success' => $success]);
	}

	#[Route('/comment', Route::POST)]
	#[Authenticated]
	#[ResponseBody]
	public function comment() {
		$user = $this->session->getUser();
		if ($user == null || !isset($user['id']) || $user['id'] == null) {
			return "Usuário não autenticado, faça login";
		}

		$postId = $_POST['postId'];
		$comment = $_POST['comment'];

		if ($postId == null || $postId == '') return "ID do post não informado";
		if ($comment == null || $comment == '') return "Comentário vazio";

		$postId = (int) $postId;

		$comment = $this->commentService->commentPost($user['id'], $postId, $comment);

		if ($comment == null)
			return $this->returnJson(['success' => false]);

		$comment = CommentDto::by($comment)->toArray();
		$comment['user'] = $user;

		return $this->returnJson(['success' => true, 'comment' => $comment]);
	}

}