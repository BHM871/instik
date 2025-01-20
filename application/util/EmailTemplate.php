<?php

class EmailTemplate {

	public static function changePassword(string $hash) {
		return 
			"<style>" .
				"h1 {text-align: center}" .
			"</style>" .
			"<body>" .
				"<h1>Troque sua Senha</h1>" .
				"<p>Para trocar sua senha <a href=\"" . BASE_URL . "/auth/change-password-view?hash=$hash\">click aqui</a></p>" .
			"</body>"
		;
	}

}