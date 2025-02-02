<?php

namespace System\Interfaces\Application;

use System\Database;

abstract class IRepository {

	protected Database $db;

	public function __construct() {
		$this->db = Database::instance();
	}

}