<?php

namespace Instik\Util;

use Instik\Configs\Navigation;

class EmailTemplate {

	public static function confirmRegister(string $email) : string {
		return
			"<style>" .
				"h1 {text-align: center}" .
				"body {" .
					"display: flex;" .
					"justify-content: space-evenly;" .
					"align-items: center;" .
					"margin: 0;" .
					"background: linear-gradient(180deg, var(#2a52d6), var(#8c0dbe), var(#db14ff));" .
				"}" .
				".btn-submit {" .
					"width: 18rem;" .
					"height: 3.5rem;" .
					"font-size: 1.5rem;" .
					"font-weight: bold;" .
					"text-transform: uppercase;" .
					"background: linear-gradient(135deg, var(#2a52d6), var(#8c0dbe));" .
					"color: var(--white);" .
					"border-radius: 40px;" .
					"transition: 1s;" .
					"border: none;" .
					"margin: 10px;" .
				"}" .
			"</style>" .
			"<body>" .
				"<h1>Venha e confirme seu cadastro</h1>" .
				"<a class=\"btn-submit\" href=\"" . BASE_URL . Navigation::register . "?email=$email&password=empty&confirm-password=empty\">Finalizar</a>" .
			"</body>";
	}

	public static function changePassword(string $hash) : string {
		$hash = urldecode($hash);
		return 
			"<style>" .
				"h1 {text-align: center}" .
			"</style>" .
			"<body>" .
				"<h1>Troque sua Senha</h1>" .
				"<p>Para trocar sua senha <a href=\"" . BASE_URL . Navigation::change_password_view . "?hash=$hash\">click aqui</a></p>" .
			"</body>"
		;
	}

}