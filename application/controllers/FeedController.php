<?php

namespace Instik\Controllers;

use Instik\Configs\Pages;
use Instik\DTO\FeedFiltersDto;
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

		$filters = $this->getFilters();
		$posts = $this->postService->getFeed($user['id'], $filters);

		if ($posts != null) {
			$postsObj = $posts;
			$posts = [];
			foreach ($postsObj as $post) $posts[] = $post->toArray();
		}

		$this->loader->load(Pages::home, ['user' => $user, 'filters' => $filters->toArray(), 'posts' => $posts]);
	}

	private function getFilters() : FeedFiltersDto {
		$text = null; $orderBy = null; $order = null;
		
		if (isset($_GET['search']))
			$text = $_GET['search'];

		if (isset($_GET['orderBy']))
			$orderBy = $_GET['orderBy'];

		if (isset($_GET['order']))
			$order = $_GET['order'];

		return new FeedFiltersDto($text, $orderBy, $order);
	}

}