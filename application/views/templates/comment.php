<div class="comment">
	<div class="comment-user">
		<img class="profile" src="<?= BASE_PATH . "/" . $comment['user']['image_path'] ?>" />
		<small><a><?= $comment['user']['username'] ?></a></small>
	</div>
	<div class="comment-content">
		<small><?= $comment['content'] ?></small>
	</div>
	<div class="comment-replies">
		<?php 
			if (isset($comment['replies'])) {
		 		foreach($comment['replies'] as $comment) {
					$this->load(\Instik\Configs\Templates::comment, $comment);
				}
			}
		?>
	</div>
</div>