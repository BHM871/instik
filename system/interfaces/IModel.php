<?php

require_once("./system/Database.php");

abstract class IModel {

	protected Database $db;

	public function __construct() {
		$this->db = Database::instance();
	}

}