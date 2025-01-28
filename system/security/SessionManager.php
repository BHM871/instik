<?php

namespace System\Security;

class SessionManager {

	private const TOKEN_KEY = "_PHP_TOKEN_SESSION";

	public function __construct(
		private readonly TokenManager $tokenManager
	) {}

	public function isAuthenticated() : bool {
		if (!isset($_COOKIE[SessionManager::TOKEN_KEY])) {
			return false;
		}
		
		$token = $_COOKIE[SessionManager::TOKEN_KEY];

		if ($token == null || strlen($token) == 0) {
			return false;
		}

		return $this->tokenManager->validate($token);
	}

	public function putUser(object|array $user) : bool {
		if ($user == null || (!is_object($user) && !is_array($user)) || (is_array($user) && sizeof($user) == 0)) {
			return false;
		}
		
		$token = $this->tokenManager->generate($user);

		if (isset($_COOKIE[SessionManager::TOKEN_KEY]))
			unset($_COOKIE[SessionManager::TOKEN_KEY]);
		
		setcookie(SessionManager::TOKEN_KEY, $token, (time() + (SESSION_TIME * 60)), "/");

		return true;
	}

	public function getUser() : object|array|null {
		if (!isset($_COOKIE[SessionManager::TOKEN_KEY])) {
			return null;
		}
		
		$token = $_COOKIE[SessionManager::TOKEN_KEY];

		if ($token == null || strlen($token) == 0) {
			return null;
		}

		if (!$this->tokenManager->validate($token)) {
			return null;
		}

		return $this->tokenManager->getContent($token);
	}

	public function removeUser() {
		if (isset($_COOKIE[SessionManager::TOKEN_KEY]))
			unset($_COOKIE[SessionManager::TOKEN_KEY]);
	}

	public function put(string $key, string $value, int $time = SESSION_TIME) : bool {
		if ($key == null) {
			return false;
		}

		if (isset($_COOKIE[$key]))
			unset($_COOKIE[$key]);

		setcookie($key, $value, (time() + ($time * 60)), "/");
		return true;
	}

	public function get(string $key) : ?string {
		if ($key == null) {
			return null;
		}
		
		if (!isset($_COOKIE[$key])) {
			return null;
		}

		return $_COOKIE[$key];
	}

}