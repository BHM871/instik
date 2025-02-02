<?php

namespace Instik\Controllers;

use Instik\Configs\Pages;
use Instik\DTO\Entity\PostDto;
use Instik\DTO\FeedFiltersDto;
use Instik\Services\PostService;

use System\Annotations\Route\Routable;
use System\Annotations\Route\Route;
use System\Annotations\Security\Authenticated;
use System\Interfaces\Application\IController;
use System\Security\SessionManager;

#[Routable("/feed")]
class FeedController extends IController {

	public function __construct(
		private readonly PostService $postService,
		SessionManager $session
	) {
		parent::__construct($session);
	}

	#[Route("/", Route::GET)]
	#[Authenticated]
	public function feed() {
		$user = $this->session->getUser();

		if ($user == null || $user['id'] == null) {
			$this->redirect("/");
			return;
		}

		$filters = $this->getFilters();
		$posts = $this->postService->getFeed($user['id'], $filters);

		if ($posts != null) {
			$postsObj = $posts; $posts = [];
			foreach ($postsObj as $post) $posts[] = PostDto::by($post)->toArray();
		}

		return $this->returnPage(Pages::home, ['user' => $user, 'filters' => $filters->toArray(), 'posts' => $posts]);
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