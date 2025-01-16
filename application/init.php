<?php

// Setup your application here

$db = Database::instance();
$db->getDefaultConnection()->query(
	"CREATE DATABASE IF NOT EXISTS instik CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci"
);

$success = $db->queries(preg_split("/\;\s*/", file_get_contents("./application/configs/setup-database.sql")));

if ($success == false) {
	$db->query("ROLLBACK");
}