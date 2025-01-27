<?php

namespace Instik\Controllers;

use Instik\Configs\Pages;
use Instik\Services\PostService;

use System\Annotations\Route\Routable;
use System\Annotations\Route\Route;
use System\Interfaces\IController;
use System\Security\SessionManager;

#[Routable("/feed")]
class FeedController extends IController {

	public function __construct(
		private readonly PostService $postService,
		SessionManager $session
	) {
		parent::__construct($session);
	}

	#[Route("/")]
	public function feed() {
		if (!$this->session->isAuthenticated())
			$this->redirect("/");

		$user = $this->session->getUser();

		if ($user == null || $user['id'] == null)
			$this->redirect("/");

		$posts = $this->postService->getFeed($user['id']);

		$this->loader->load(Pages::home, ['user' => $user, 'posts' => $posts]);
	}

}