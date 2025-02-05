<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Instik - Postar</title>

	<?php $this->load(Instik\Configs\Templates::head_links) ?>

	<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/add_post.css" />

</head>
<body>
	<a id="navbar" href="<?= BASE_URL . Instik\Configs\Navigation::feed ?>">
		<p>Feed</p>
		<?php $this->load(Instik\Configs\Icons::arrow_back) ?>
	</a>
	<form action="<?= BASE_URL . Instik\Configs\Navigation::publish ?>" method="POST" enctype="multipart/form-data">
		<main>
			<div id="head">
				<div id="user-data">
					<img class="profile" src="<?= isset($user['image_path']) ? (BASE_PATH . "/" . $user['image_path']) : '' ?>" />
					<p><?= $user['username'] ?></p>
				</div>
				<button id="post-btn" class="btn-submit">Publicar</button>
			</div>
			<div id="content">
				<textarea id="post-caption" name="caption" class="input-black" placeholder="Escreva seus pensamentos aqui..."></textarea>
				<div>
					<label id="image-placeholder" for="post-image" class="input-image placeholder"><img /></label>
					<input id="post-image" class="image" name="image" type="file" />
				</div>
			</div>
		</main>
	</form>
	
	<?php if (isset($message)) : ?>
		<div id="message" class="message">
			<p><?= $message ?></p>
		</div>
	<?php endif ?>
</body>

<script type="text/javascript" src="<?= BASE_URL ?>/assets/js/default.js"></script>
<script type="text/javascript" src="<?= BASE_URL ?>/assets/js/input-image.js"></script>

</html>