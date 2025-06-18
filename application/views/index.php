<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Instik - Login</title>

	<?php $this->load(Instik\Configs\Templates::head_links) ?>

	<!-- CSS -->
	<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/login.css" />
	<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/modal.css" />

</head>
<body>
	<aside id="left">
		<div>
			<img id="logo" src="<?= BASE_URL ?>/assets/images/icon.jpg" alt="Logo do Site">
			<h1>Instik</h1>
			<p>Para Inspirar-se</p>
		</div>
	</aside>
	<aside id="rigth">
		<main id="main">
			<form id="login" class="content" action="<?= BASE_URL . Instik\Configs\Navigation::authenticate ?>" method="POST">
				<h1 class="title">Conectar-se</h1>

				<hr />

				<div class="inputs">
					<div class="input">
						<label for="email">Email/Usuário</label>
						<input id="email" name="email" validate-type="text" placeholder="Email ou nome de usuário" required />
					</div>

					<div class="input">
						<label for="password">Senha</label>
						<input id="password" name="password" validate-type="password" type="password" placeholder="Senha" required />
						
						<div class="aux">
							<small><b><a href="#forgot-password-modal" data-toggle="modal" class="link">Esqueci a senha</a></b></small>
						</div>
					</div>
				</div>

				<input id="login-btn" type="submit" class="btn-submit" value="Login" />

				<div class="aux">
					<small>Não possui conta? <b><span id="register-link" class="link">Cadastre-se</span></b></small>
				</div>
			</form>
			<form id="register" class="content" action="<?= BASE_URL . Instik\Configs\Navigation::register ?>" method="POST">
				<h1 class="title">Registrar-se</h1>

				<hr />

				<div class="inputs">
					<div class="input">
						<label for="email">Email</label>
						<input id="email" name="email" validate-type="email" type="email" placeholder="Email" required />
					</div>

					<div class="input">
						<label for="password">Senha</label>
						<input id="password" name="password" validate-type="password" type="password" placeholder="Senha" required />
					</div>

					<div class="input">
						<label for="password-confirm">Confirmação</label>
						<input id="password-confirm" name="password-confirm" validate-type="password" type="password" placeholder="Confirmação da senha" required />
					</div>
				</div>

				<input id="register-btn" type="submit" class="btn-submit" value="Registrar" />

				<div class="aux">
					<small>Já possui conta? <b><span id="login-link" class="link">Faça login</span></b></small>
				</div>
			</form>
		</main>
	</aside>

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
<script type="text/javascript" src="<?= BASE_URL ?>/assets/js/login.js"></script>
<script type="text/javascript" src="<?= BASE_URL ?>/assets/js/modal.js"></script>
<script type="text/javascript" src="<?= BASE_URL ?>/assets/js/validator.js"></script>

</html>