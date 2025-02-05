<?php

use Instik\Configs\Navigation;
use Instik\Configs\Pages;
use Instik\DTO\Entity\CommentDto;
use Instik\Services\CommentService;
use Instik\Services\LikeService;
use Instik\Services\PostService;
use Instik\Validators\PostValidator;

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
		private readonly PostValidator $validator,
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

	#[Route('/publish', Route::POST)]
	#[Authenticated]
	public function publish() {
		$user = $this->session->getUser();
		if ($user == null || !isset($user['id']) || $user['id'] == null) {
			$this->redirect("/");
			return;
		}

		$isValid = $this->validator->validNewPost($_POST['caption'], $_FILES['image']);

		if (!$isValid)
			return $this->returnPage(Pages::add_post, ['user' => $user, 'message' => 'Campos enviados não são válidos']);

		$post = $this->service->publish($user['id'], $_POST['caption'], $_FILES['image']);

		if ($post == null || $post->getId() == null)
			return $this->returnPage(Pages::add_post, ['user' => $user, 'message' => 'Houve algum erro ao publicar o post']);

		$this->redirect(Navigation::feed);
	}

	#[Route('/like', Route::POST)]
	#[Authenticated]
	#[ResponseBody]
	public function like() {
		$user = $this->session->getUser();
		if ($user == null || !isset($user['id']) || $user['id'] == null) {
			return "Usuário não autenticado, faça login";
		}

		$isValid = $this->validator->validLikeAndUnlike($_POST['postId']);

		if (!$isValid) return "ID do post não informado";

		$success = $this->likeService->likePost($_POST['postId'], $user['id']);

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

		$isValid = $this->validator->validLikeAndUnlike($_POST['postId']);

		if (!$isValid) return "ID do post não informado";

		$success = $this->likeService->unlikePost((int) $_POST['postId'], $user['id']);

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

		$isValid = $this->validator->validComment($_POST['postId'], $_POST['comment']);

		if (!$isValid) {
			if (trim($_POST['comment']) == '')	return 'Comentário vazio';

			return "ID do post não informado";
		}

		$comment = $this->commentService->commentPost($user['id'], (int) $_POST['postId'], $_POST['comment']);

		if ($comment == null)
			return $this->returnJson(['success' => false]);

		$comment = CommentDto::by($comment)->toArray();
		$comment['user'] = $user;

		return $this->returnJson(['success' => true, 'comment' => $comment]);
	}

}