<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Instik - Login</title>

	<?php $this->load(Templates::head_links) ?>

	<!-- CSS -->
	<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/login.css" />
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
			<form id="login" class="content" action="<?= BASE_URL ?>/auth/authenticate" method="POST">
				<h1 class="title">Conectar-se</h1>

				<hr />

				<div class="inputs">
					<div class="input">
						<label for="email">Email/Usuário</label>
						<input id="email" name="email" type="email" placeholder="Email ou nome de usuário" />
					</div>

					<div class="input">
						<label for="password">Senha</label>
						<input id="password" name="password" type="password" placeholder="Senha" />
					</div>
				</div>

				<input id="login-btn" type="submit" value="Login" />

				<div class="aux">
					<small>Não possui conta? <b><span id="register-link" class="link">Cadastre-se</span></b></small>
				</div>
			</form>
			<form id="register" class="content" action="<?= BASE_URL ?>/auth/register" method="POST">
				<h1 class="title">Registrar-se</h1>

				<hr />

				<div class="inputs">
					<div class="input">
						<label for="email">Email/Usuário</label>
						<input id="email" name="email" type="email" placeholder="Email ou nome de usuário" />
					</div>

					<div class="input">
						<label for="password">Senha</label>
						<input id="password" name="password" type="password" placeholder="Senha" />
					</div>

					<div class="input">
						<label for="password-confirm">Confirmação</label>
						<input id="password-confirm" name="password-confirm" type="password" placeholder="Confirmação da senha" />
					</div>
				</div>

				<input id="register-btn" type="submit" value="Registrar" />

				<div class="aux">
					<small>Já possui conta? <b><span id="login-link" class="link">Faça login</span></b></small>
				</div>
			</form>
		</main>
	</aside>
</body>

	<script type="text/javascript" src="<?= BASE_URL ?>/assets/js/login.js"></script>

</html>