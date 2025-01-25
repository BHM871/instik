<?php

namespace Instik\Util;

use Instik\Configs\Navigation;

class EmailTemplate {

	public static function changePassword(string $hash) {
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