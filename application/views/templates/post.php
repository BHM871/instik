<div class="post">
	<div class="post-head">
		<div class="post-user">
			<img class="profile" src="<?= BASE_PATH . "/" . $post['publisher']['image_path'] ?>" />
			<p><a href="<?= BASE_URL . "/user?id=" . $post['publisher']['id'] ?>"><?= $post['publisher']['username'] ?></a></p>
		</div>
	</div>
	<div class="post-body">
		<p><?= isset($post['caption']) ? $post['caption'] : "" ?></p>
		<img src="<?= isset($post['image_path']) ? BASE_PATH . "/" . $post['image_path'] : "" ?>">
	</div>
	<div class="post-iteractions">
		<button id="<?= $post['id'] ?>" type="like" <?= isset($post['isLiked']) && $post['isLiked'] ? 'liked="true"' : '' ?>>
			<?php $this->load(\Instik\Configs\Icons::favorite) ?>
			<small><?= isset($post['likes']) ? $post['likes'] : 0 ?></small>
		</button>
		<button id="<?= $post['id'] ?>" type="comment">
			<?php $this->load(\Instik\Configs\Icons::share) ?>
		</button>
	</div>
	<div class="post-comment">
		<input id="<?= $post['id'] ?>" type="text" class="input-black" placeholder="Comente..." />
		<?php 
			if (isset($post['comments'])) {
		 		foreach($post['comments'] as $comment) {
					$this->load(\Instik\Configs\Templates::comment, $comment);
				}
			}
		?>
	</div>
</div>