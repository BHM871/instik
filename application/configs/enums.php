<?php

namespace Instik\Configs;

class Navigation {
	// AuthController
	const authenticate = "/auth/authenticate";
	const send_password_email = "/auth/send-password-email";
	const change_password_view = "/auth/change-password-view";
	const change_password = "/auth/change-password";
	const logout = "/auth/logout";

	// UserController
	const register = "/user/register";
	const confirm_register = "/user/confirm-register";

	// FeedController
	const feed = "/feed/";

	// PostController
	const like = "/post/like";
	const unlike = "/post/unlike";
	const comment = "/post/comment";
	const post = "/post/post";
	const publish = "post/publish";
}

class Templates {
	const head_links = "templates/head_links";
	
	const post = "templates/post";
	const comment = "templates/comment";
}

class Pages {
	const login = "index";
	const register_confirm = "register_confirm";
	const change_password = "change_password";
	const home = "feed";
	const add_post = "add_post";
}

class Icons {
	const search = "svgs/search-icon";
	const replay = "svgs/replay-icon";
	const favorite = "svgs/favorite-icon";
	const share = "svgs/share-icon";
	const send = "svgs/send-icon";
	const arrow_back = "svgs/arrow-back-icon";
}