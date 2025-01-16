<?php

require_once("./system/Logger.php");

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

	/**
	 * 	Create a conection and execute a query.
	 * 
	 * 	@param string $query any query, like "SELECT * FROM table_name"
	 * 	@param array $data [optional] the parameters from query is used like, "SELECT * FROM <i>table_name</i> WHERE id = ?, name = ?;"<br>
	 * 	<i>i</i> param must be in $data[0], <i>name</i> param must be in $data[1].
	 */
	public function query(string $query, $data = array()) : array|null {
		if ($query == null) {
			return null;
		}

		$con = $this->getConnection();

		try {
			$con->beginTransaction();
			$stmt = $con->prepare($query);

			for ($i = 0; $i < sizeof($data); $i++) {
				$stmt->bindParam($i+1, $data[$i]);
			}

			$out = [];
			$stmt->execute();
			while ($row = $stmt->fetch(PDO::FETCH_NAMED)) {
				$out[] = $row;
			}

			if ($con->inTransaction())
				$con->commit();

			return $out;
		} catch (\Throwable $th) {
			$this->logger->log($th->getMessage());

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

				$stmt = $con->prepare($query);

				for ($j = 0; isset($data[$i]) && $j < sizeof($data[$i]); $j++) {
					$stmt->bindParam($i+1, $data[$i][$j]);
				}

				$stmt->execute();
			}

			if ($con->inTransaction())
				$con->commit();

			return true;
		} catch (\Throwable $th) {
			$this->logger->log($th->getMessage());

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
	public function get(string $table, $columns = array(), $where = array()) : array|null {
		if ($table == null) {
			return null;
		}

		$col = "";
		if ($columns == null || sizeof($columns) == 0) {
			$col = " * ";
		} else {
			for ($i = 0; $i < sizeof($columns); $i++) {
				$col .= $columns[$i] . ($i == sizeof($columns) - 1) ? " " : ", ";
			}
		}

		$whe = "";
		if ($where != null && sizeof($where) != 0) {
			$i = 0;
			foreach ($where as $column => $value) {
				$whe .= ($i > 0 ? "AND " : "") . $column . "=" . $value;
				$i++;
			}
		}

		$con = $this->getConnection();

		try {
			$rs = $con->query("SELECT $col FROM $table $whe");

			$out = [];
			while ($row = $rs->fetch(PDO::FETCH_NAMED)) {
				$out[] = $row;
			}

			return $out;
		} catch (\Throwable $th) {
			$this->logger->log($th->getMessage());

			return null;
		}
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
	public function insert(string $table, array $data) : bool {
		if ($table == null || $data == null || sizeof($data) == 0) {
			return false;
		}

		$con = $this->getConnection();

		try {
			$con->beginTransaction();

			$insert = "INSERT INTO $table(";
			$values = "VALUES(";

			$i = 0;
			foreach ($data as $key => $value) {
				$insert .= $i > 0 ? ", $key" : $key;
				$values .= $i > 0 ? ", '$value'": "'$value'";
				$i++;
			}

			$query = $insert.') '.$values.')';
			$con->query($query);

			if ($con->inTransaction())
				$con->commit();

			return true;
		} catch (\Throwable $th) {
			$this->logger->log($th->getMessage());

			if ($con->inTransaction())
				$con->rollBack();

			return false;
		}
	}
}