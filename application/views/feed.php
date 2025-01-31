<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Instik - Feed</title>

	<?php $this->load(Instik\Configs\Templates::head_links) ?>

	<!-- CSS -->
	<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/feed.css" />
	<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/post.css" />
	<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/modal.css" />
</head>
<body>
	<input id="userId" type="hidden" value="<?= $user['id'] ?>" />
	<input id="ctx" type="hidden" value="<?= BASE_URL ?>" />
	<section id="navbar">
		<div id="head">
			<img class="profile" src="<?= BASE_PATH . "/" . $user['image_path'] ?>" />
			<p><?= $user['username'] ?></p>
		</div>
		<ul id="options">
			<li><a>Adicionar Post</a></li>
			<li><a>Perfil</a></li>
			<li><a href="#forgot-password-modal" data-toggle="modal">Trocar Senha</a></li>
			<li><a href="<?= BASE_URL . \Instik\Configs\Navigation::logout ?>">Sair</a></li>
		</ul>
	</section>
	
	<section id="main">
		<section id="searcher">
			<form id="filters-form" action="<?= BASE_URL . \Instik\Configs\Navigation::feed ?>">
				<div id="finder">
					<input id="search" name="search" type="text" class="input-black" placeholder="Pesquise..." value="<?= isset($filters['text']) ? $filters['text'] : "" ?>"/>
					<button id="btn-search" type="submit">
						<?php $this->load(Instik\Configs\Icons::search) ?>
					</button>
				</div>
				<div id="filters">
					<label>Filtros:</label>
					<select name="orderBy" class="filter">
						<option value="">Ordernar Por</option>
						<option value="caption" <?= isset($filters['orderBy']) && $filters['orderBy'] == 'caption' ? 'selected' : '' ?>>Descrição</option>
						<option value="posted_date" <?= isset($filters['orderBy']) && $filters['orderBy'] == 'posted_date' ? 'selected' : '' ?>>Data</option>
					</select>
					<select name="order" class="filter">
						<option value="DESC" <?= isset($filters['order']) && $filters['order'] == 'DESC' ? 'selected' : '' ?>>Descressente</option>
						<option value="ASC" <?= isset($filters['order']) && $filters['order'] == 'ASC' ? 'selected' : '' ?>>Assendente</option>
					</select>
				</div>
			</form>
		</section>

		<section id="feed">
			<main>
				<?php
					if (isset($posts) && sizeof($posts) > 0) {
						foreach ($posts as $post) {
							$this->load(\Instik\Configs\Templates::post, ['post' => $post]);
						}
					} else {
						echo '<h1 style="text-align: center">Não há publicações</h1>';
					}
				?>
			</main>
		</section>
	</section>

	<div id="forgot-password-modal" class="modal">
		<div class="modal-content">
			<div class="modal-header">
				<button class="modal-close" data-dismiss="modal">X</button>
			</div>
			<div class="model-body">
				<form id="change-password" class="content" action="<?= BASE_URL . Instik\Configs\Navigation::send_password_email ?>" method="POST">
					<div class="inputs">
						<div class="input">
							<label for="email">Email</label>
							<input id="email" name="email" validate-type="email" type="email" placeholder="Email para trocar senha" required />
						</div>
					</div>

					<input id="register-btn" type="submit" value="Enviar" class="btn-submit" data-dismiss="modal" />
				</form>
			</div>
		</div>
	</div>
	
	<?php if (isset($message)) : ?>
		<div id="message" class="message">
			<p><?= $message ?></p>
		</div>
	<?php endif ?>
</body>

<script type="text/javascript" src="<?= BASE_URL ?>/assets/js/default.js"></script>
<script type="text/javascript" src="<?= BASE_URL ?>/assets/js/feed.js"></script>
<script type="text/javascript" src="<?= BASE_URL ?>/assets/js/post.js"></script>
<script type="text/javascript" src="<?= BASE_URL ?>/assets/js/modal.js"></script>
<script type="text/javascript" src="<?= BASE_URL ?>/assets/js/validator.js"></script>

</html>