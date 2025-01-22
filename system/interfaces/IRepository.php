<?php

namespace System\Interfaces;

use System\Database;

abstract class IRepository {

	protected Database $db;

	public function __construct() {
		$this->db = Database::instance();
	}

}