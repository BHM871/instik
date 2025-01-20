<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Instik - Trocar Senha</title>

	<?php $this->load(Templates::head_links) ?>

	<!-- CSS -->
	<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/change_password.css" />
</head>
<body>
	<main id="main">
		<form class="content" action="<?= BASE_URL ?>/auth/change-password" method="POST">
			<h1 class="title">Trocar Senha</h1>

			<hr />

			<div class="inputs">
				<input name="hash" type="hidden" value="<?= $hash ?>" required />
				
				<div class="input">
					<label for="password">Senha</label>
					<input id="password" name="password" validate-type="password" type="password" placeholder="Nova Senha" required />
				</div>

				<div class="input">
					<label for="password-confirm">Confirmação</label>
					<input id="password-confirm" name="password-confirm" validate-type="password" type="password" placeholder="Confirmação da nova senha" required />
				</div>
			</div>

			<input id="change-btn" type="submit" class="btn-submit" value="Trocar" />
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

</html>