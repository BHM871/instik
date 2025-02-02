<?php

namespace System;

use PDO;
use Throwable;

class Database {

	private Logger $logger;
	private static Database $th;

	private function __construct() {
		$this->logger = new Logger($this);
	}

	public static function instance() : Database {
		if (!isset(Database::$th))
			Database::$th = new Database();

		return Database::$th;	
	}

	private function getConnection() : PDO {
		return new PDO('mysql:host='.DB_HOST.';port='.DB_PORT.';dbname='.DB_NAME, DB_USER, DB_PASSWORD);
	}

	public function getDefaultConnection() : PDO {
		return new PDO('mysql:host='.DB_HOST.';port='.DB_PORT.';dbname=mysql', DB_USER, DB_PASSWORD);
	}

	public static function setup() {
		try {
			$db = Database::instance();
			$con = $db->getDefaultConnection();

			$con->query(
				"CREATE TABLE IF NOT EXISTS mysql.operations (" .
					"id INTEGER PRIMARY KEY AUTO_INCREMENT," .
					"table_name VARCHAR(100) NOT NULL, " .
					"operation ENUM('insert', 'update', 'delete') NOT NULL, " .
					"moment TIMESTAMP DEFAULT CURRENT_TIMESTAMP(), " .
					"identificator VARCHAR(50) DEFAULT NULL" .
				")"
			);

			$result = $con
				->query("SELECT TABLE_NAME AS 'table' FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = '".DB_NAME."'")
				->fetchAll(PDO::FETCH_NAMED);

			if (sizeof($result) > 0) {
				$con = $db->getConnection();
			}

			foreach ($result as $row) {	
				$con->query( 
					"CREATE TRIGGER IF NOT EXISTS insert_".$row['table']."_trigger AFTER INSERT " . PHP_EOL .
					"ON `".$row['table']."` " . PHP_EOL .
					"FOR EACH ROW " . PHP_EOL .
					"BEGIN " . PHP_EOL .
						"INSERT INTO mysql.operations(table_name, operation, identificator) VALUES ('".$row['table']."', 'insert', NEW.id); " . PHP_EOL .
					"END "
				);

				$con->query( 
					"CREATE TRIGGER IF NOT EXISTS update_".$row['table']."_trigger AFTER UPDATE " . PHP_EOL .
					"ON `".$row['table']."` " . PHP_EOL .
					"FOR EACH ROW " . PHP_EOL .
					"BEGIN " . PHP_EOL .
						"INSERT INTO mysql.operations(table_name, operation, identificator) VALUES ('".$row['table']."', 'update', NEW.id); " . PHP_EOL .
					"END "
				);

				$con->query( 
					"CREATE TRIGGER IF NOT EXISTS delete_".$row['table']."_trigger AFTER DELETE " . PHP_EOL .
					"ON `".$row['table']."` " . PHP_EOL .
					"FOR EACH ROW " . PHP_EOL .
					"BEGIN " . PHP_EOL .
						"INSERT INTO mysql.operations(table_name, operation) VALUES ('".$row['table']."', 'delete'); " . PHP_EOL .
					"END "
				);
			}
		} catch (Throwable $th) {
			$logger = (new Logger(Database::instance()));
			$logger->log("Cannot setup database");
			$logger->log($th);
		}
	}

	/**
	 * 	Create a conection and execute a query.
	 * 
	 * 	@param string $query any query, like "SELECT * FROM table_name"
	 * 	@param array $data [optional] the parameters from query is used like, "SELECT * FROM <i>table_name</i> WHERE id = ?, name = ?;"<br>
	 * 	<i>i</i> param must be in $data[0], <i>name</i> param must be in $data[1].
	 */
	public function query(string $query, $data = array()) : ?array {
		if ($query == null) {
			return null;
		}

		$con = $this->getConnection();

		try {
			$stmt = $con->prepare($query);

			for ($i = 0; $i < sizeof($data); $i++) {
				$stmt->bindParam($i+1, $data[$i]);
			}

			$con->beginTransaction();
			$stmt->execute();
			$out = $stmt->fetchAll(PDO::FETCH_NAMED);

			if ($con->inTransaction())
				$con->commit();

			return $out;
		} catch (\Throwable $th) {
			$this->logger->log($th);

			if ($con->inTransaction())
				$con->rollBack();

			return null;
		}
	}

	/**
	 * 	Like @method query, but execute N queries with one connection.
	 * 
	 * 	@param string $query an array of query, like ["UPDATE table SET id = ?"]
	 * 	@param array $data [optional] the parameters from query is used like, "SELECT * FROM <i>table_name</i> WHERE id = ?, name = ?;"<br>
	 * 	<i>i</i> param must be in $data[0], <i>name</i> param must be in $data[1].<br>
	 * 	This is a matrix.
	 */
	public function queries(array $queries, $data = array()) : bool {
		if ($queries == null || sizeof($queries) == 0) {
			return false;
		}

		$con = $this->getConnection();

		try {
			$con->beginTransaction();

			for ($i = 0; $i < sizeof($queries); $i++) {
				$query = $queries[$i];

				if ($query == "") continue;

				$stmt = $con->prepare($query);

				for ($j = 0; isset($data[$i]) && $j < sizeof($data[$i]); $j++) {
					$type = (is_int($data[$i][$j])
						? PDO::PARAM_INT
						: (is_bool($data[$i][$j])
							? PDO::PARAM_BOOL
							: PDO::PARAM_STR
						)
					);

					$stmt->bindParam($j+1, $data[$i][$j]);
				}

				$stmt->execute();
			}

			if ($con->inTransaction())
				$con->commit();

			return true;
		} catch (\Throwable $th) {
			$this->logger->log($th);

			if ($con->inTransaction())
				$con->rollBack();

			return false;
		}

	}

	/**
	 * 	Get data in a table.
	 * 
	 * 	@param string $table the table where will be get;
	 * 	@param array $columns [optional] the columns that will be get;
	 * 	@param array $where [optional] the params of where, like,
	 * 	<pre>
	 * 	[
	 * 		"column1" => "value1",
	 * 		"column2" => "value1"
	 * 	]
	 * 	</pre>
	 */
	public function get(string $table, $columns = array(), $where = array()) : ?array {
		if ($table == null) {
			return null;
		}

		$col = "";
		if ($columns == null || sizeof($columns) == 0) {
			$col = " * ";
		} else {
			for ($i = 0; $i < sizeof($columns); $i++) {
				$col .= ($i > 0 ? ", " : "") . $columns[$i];
			}
		}

		$data = [];
		$whe = "";
		if ($where != null && sizeof($where) != 0) {
			$i = 0;
			foreach ($where as $column => $value) {
				if (is_array($value)) {
					$data += $value;

					$whe .= ($i > 0 ? "AND " : "") . "`$column` IN";
					
					$whe .= "(";
					for ($j = 0; $j < sizeof($value); $j++) {
						$whe .= ($j > 0 ? ", " : "") . "?";
					}
					$whe .= ")";
					
					continue;
				}

				$data[] = $value;
				$whe .= ($i > 0 ? " AND " : "") . (is_string($value) ? "`$column` LIKE ?" : "`$column` = ?");
				$i++;
			}
		}

		$query = "SELECT $col FROM `$table` WHERE $whe";
		return $this->query($query, $data);
	}

	/**
	 * 	Insert a tuple in a table.
	 * 
	 * 	@param string $table the table where will be insert;
	 * 	@param array $data the columns and datas to insert<br>
	 * 	<pre>
	 * 	[
	 * 		"column1" => "value1",
	 * 		"column2" => "value1"
	 * 	]
	 * 	</pre>
	 */
	public function insert(string $table, array $data) : ?array {
		if ($table == null || $data == null || sizeof($data) == 0) {
			return null;
		}

		$con = $this->getConnection();

		try {
			$insert = "INSERT INTO `$table`(";
			$values = "VALUES(";

			$i = 0;
			$cols = [];
			$vals = [];
			foreach ($data as $column => $value) {
				$insert .= ($i > 0 ? ", " : "") . "`$column`";
				$values .= ($i > 0 ? ", " : "") . ":$column";
				
				$cols[$i] = $column;
				$vals[$i] = $value;
				
				$i++;
			}

			$query = $insert.') '.$values.')';
			$stmt = $con->prepare($query);

			for ($i = 0; $i < sizeof($data); $i++) {
				$type = (is_int($vals[$i])
					? PDO::PARAM_INT
					: (is_bool($vals[$i])
						? PDO::PARAM_BOOL
						: PDO::PARAM_STR
					)
				);

				$stmt->bindParam($cols[$i], $vals[$i], $type);
			}

			$con->beginTransaction();
			$stmt->execute();
			
			$result = $con->query(
				"SELECT * " .
				"FROM `$table` " .
				"WHERE id = ".$this->getLastId($con, 'insert', $table)
			);

			if ($con->inTransaction())
				$con->commit();

			return $result->fetchAll(PDO::FETCH_NAMED);
		} catch (\Throwable $th) {
			$this->logger->log($th);

			if ($con->inTransaction())
				$con->rollBack();

			return null;
		}
	}

	public function update(string $table, array $data) : ?array {
		if ($table == null || !is_string($table) || trim($table) == "" || $data == null || !is_array($data) || sizeof($data) == 0)
			return null;

		$con = $this->getConnection();

		try {
			$uniqueColumn = $this->existsAnUniqueColumn($con, $table, $data);

			if ($uniqueColumn == null) {
				$this->logger->log("An unique column wasn't inform");
				return null;
			}

			$query = "UPDATE $table SET ";

			$i = 0;
			$isFirst = true;
			$cols = [];
			$vals = [];
			foreach ($data as $column => $value) {
				if ($column != $uniqueColumn) {
					$query .= ($isFirst ? "" : ", ") . "`$column` = :$column";
					$isFirst = false;
				}
				
				$cols[$i] = $column;
				$vals[$i] = $value;
				
				$i++;
			}

			$query .= " WHERE `$uniqueColumn` = :$uniqueColumn";
			$stmt = $con->prepare($query);

			for ($i = 0; $i < sizeof($data); $i++) {
				$type = (is_int($vals[$i])
					? PDO::PARAM_INT
					: (is_bool($vals[$i])
						? PDO::PARAM_BOOL
						: PDO::PARAM_STR
					)
				);

				$stmt->bindParam($cols[$i], $vals[$i], $type);
			}

			$con->beginTransaction();
			$stmt->execute();
			
			$result = $con->query(
				"SELECT * " .
				"FROM `$table` " .
				"WHERE id = ".$this->getLastId($con, 'update', $table)
			);

			if ($con->inTransaction())
				$con->commit();

			return $result->fetchAll(PDO::FETCH_NAMED);
		} catch (Throwable $th) {
			$this->logger->log($th);

			return null;
		}
	}

	private function getLastId(PDO $con, string $operation, ?string $table = null) : string {
		$result = $con->query(
			"SELECT identificator " .
			"FROM mysql.operations " .
			"WHERE ".($table != null ? "table_name = '$table' AND " : "")."operation = '$operation' ORDER BY moment DESC LIMIT 1"
		);

		return $result->fetch(PDO::FETCH_NAMED)['identificator'];
	}

	private function existsAnUniqueColumn(PDO $con, string $table, array $data) : ?string {
		$rs = $con->query(
			"SELECT CL.COLUMN_NAME " .
			"FROM INFORMATION_SCHEMA.COLUMNS CL " .
			"WHERE CL.TABLE_NAME = '$table' " .
				"AND CL.TABLE_SCHEMA = '" . DB_NAME . "' " .
				"AND CL.COLUMN_KEY IN('PRI', 'UNI')"
		);

		while($row = $rs->fetch(PDO::FETCH_NAMED)) {
			if (isset($data[$row['COLUMN_NAME']])) {
				return $row['COLUMN_NAME'];
			}
		}

		return null;
	}
}