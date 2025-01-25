<?php

namespace Instik\Configs;

class Navigation {
	// AuthController
	const authenticate = "/auth/authenticate";
	const register = "/auth/register";
	const confirm_register = "/auth/confirm-register";
	const send_password_email = "/auth/send-password-email";
	const change_password_view = "/auth/change-password-view";
	const change_password = "/auth/change-password";
}

class Templates {
	const head_links = "templates/head_links";
}

class Pages {
	const login = "index";
	const register_confirm = "register_confirm";
	const change_password = "change_password";
	const home = "feed"; // to test
}

class Icons {
	const search = "svgs/search-icon";
	const replay = "svgs/replay-icon";
	const favorite = "svgs/favorite-icon";
}