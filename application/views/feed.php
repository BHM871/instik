<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Instik - Feed</title>

	<?php $this->load(Instik\Configs\Templates::head_links) ?>

	<!-- CSS -->
	<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/feed.css" />
</head>
<body>
	<section id="navbar">
		<div id="head">
			<img class="profile" src="<?= BASE_PATH . "/" . $user['image_path'] ?>" />
			<p><?= $user['username'] ?></p>
		</div>
		<ul id="options">
			<li><a>Adicionar Post</a></li>
			<li><a>Perfil</a></li>
			<li><a>Trocar Senha</a></li>
			<li><a>Sair</a></li>
		</ul>
	</section>
	
	<section id="main">
		<section id="searcher">
			<form id="finder" action="<?= BASE_URL ?>/feed/search">
				<input id="search" name="search" type="text" class="input-black"  value="<? //$filters['text'] ?>"/>
				<button id="btn-search" type="submit">
					<?php $this->load(Instik\Configs\Icons::search) ?>
				</button>
			</form>
			<div id="filters">
				<label>Filtros:</label>
				<select class="filter">
					<option value="">Ordernar Por</option>
					<option value="title">Título</option>
					<option value="date">Data</option>
				</select>
				<select class="filter">
					<option value="DESC">Descressente</option>
					<option value="ASC">Assendente</option>
				</select>
			</div>
		</section>

		<section id="feed"></section>
	</section>
	
	<?php if (isset($message)) : ?>
		<div id="message" class="message">
			<p><?= $message ?></p>
		</div>
	<?php endif ?>
</body>

<script type="text/javascript" src="<?= BASE_URL ?>/assets/js/default.js"></script>
<script type="text/javascript" src="<?= BASE_URL ?>/assets/js/validator.js"></script>

</html>