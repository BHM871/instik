<?php

namespace Instik\Repository;

use Instik\DTO\FeedFiltersDto;
use Instik\Entity\Post;

use System\Interfaces\Application\IRepository;

class PostRepository extends IRepository {

	public function getPostsByUser(int $userId, ?FeedFiltersDto $filters = null) : ?array {
		if ($userId == null || $userId <= 0)
			return null;
		
		$query = "";
		$datas = [];
		if ($filters != null) {
			$from = "";
			$where = "";
			$order = "";

			if ($filters->getText() != null && strlen(trim($filters->getText())) > 0) {
				$from .= " JOIN user ON(user.id = post.id_publisher)";
				$where .= " WHERE (post.caption LIKE ? OR user.username LIKE ? OR user.email LIKE ?)";
				
				$text = "%" . preg_replace("/\W/", "%", $filters->getText()) . "%";
				$datas = [$text, $text, $text];
			}

			if ($filters->getOrderBy() != null && strlen(trim($filters->getOrderBy())) > 0) {
				$order .= " ORDER BY post." . $filters->getOrderBy() . " ";
			}

			if ($filters->getOrder() != null && strlen(trim($filters->getOrder())) > 0) {
				if (strlen($order) == 0)
					$order = " ORDER BY post.posted_date ";

				$order .= $filters->getOrder();
			}

			if (strlen($order) == 0)
				$order = "ORDER BY post.posted_date DESC";

			$query .= $from . $where . $order;
		}

		$query = "SELECT post.* FROM post " . $query;
		
		$result = $this->db->query($query, $datas);

		if ($result == null || sizeof($result) == 0)
			return null;

		$posts = [];
		foreach ($result as $post) {
			$user = $this->db->get(
				'user', 
				['id', 'username', 'email', 'image_path'], 
				['id' => $post['id_publisher']]
			);

			unset($post['id_publisher']);
			$post['user'] = $user[0];

			$posts[] = Post::instancer($post);
		}

		return $posts;
	}


}