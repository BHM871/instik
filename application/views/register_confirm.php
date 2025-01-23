<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Instik - Criar Usuário</title>

	<?php $this->load(Instik\Configs\Templates::head_links) ?>

	<!-- CSS -->
	<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/register_confirm.css" />
</head>
<body>
	<main id="main">
		<form class="content" action="<?= BASE_URL ?>/auth/confirm-register" method="POST" enctype="multipart/form-data">
			<h1 class="title">Criar Usuário</h1>

			<hr />

			<div class="inputs">
				<input name="user-id" type="hidden" value="<?= $user['id'] ?>" required />

				<p><?= $user['email'] ?></p>

				<div class="input">
					<label for="profile-image" class="input-image person"><img /></label>
					<input id="profile-image" class="image" name="profile" type="file" />
				</div>
				
				<div class="input">
					<label for="username">Nome de Usuário</label>
					<input id="username" name="username" validate-type="username" type="username" placeholder="Escolha o seu nome de usuário" required />
				</div>
			</div>

			<input id="register-btn" type="submit" class="btn-submit" value="Salvar" />
		</form>
	</main>
	
	<?php if (isset($message)) : ?>
		<div id="message" class="message">
			<p><?= $message ?></p>
		</div>
	<?php endif ?>
</body>

<script type="text/javascript" src="<?= BASE_URL ?>/assets/js/default.js"></script>
<script type="text/javascript" src="<?= BASE_URL ?>/assets/js/validator.js"></script>
<script type="text/javascript" src="<?= BASE_URL ?>/assets/js/input-image.js"></script>

</html>