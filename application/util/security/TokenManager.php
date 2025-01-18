<?php

class TokenManager {

	private const limit_key = "DATETIME_LIMIT";

	private const date_format = "Y-m-d H:i:s";

	private Logger $logger;

	public function __construct() {
		$this->logger = new Logger($this);		
	}

	public function generate(object|array $user) : ?string {
		if ($user == null || (!is_array($user) && !is_object($user)) || sizeof($user) == 0) {
			return null;
		}

		$header = $this->getHeader();
		$content = $this->toString($user);
		$data = "$header|$content";

		return HashGenerator::encrypt($data);
	}

	public function validate(string $token) : bool {
		if ($token == null || !is_string($token) || strlen($token) == 0) {
			return false;
		}

		$data = HashGenerator::decrypt($token);
		$data = preg_split("/\|/", $data);

		try {
			$header = $this->toMap($data[0]);
			$content = $this->toMap($data[1]);

			if (!$this->validHeader($header)) return false;
			if (!$this->validContent($content)) return false;

			return true;
		} catch (\Throwable $th) {
			$this->logger->log("Cannot valid token");
			$this->logger->log($th);

			return false;
		}
	}

	public function getContent(string $token) : object|array|null {
		if ($token == null || !is_string($token) || strlen($token) == 0) {
			return false;
		}

		$data = HashGenerator::decrypt($token);
		$data = preg_split("/\|/", $data);

		return $this->toMap($data[1]);
	}

	private function getHeader() : string {
		$limit = (new DateTime())
			->add(DateInterval::createFromDateString(SESSION_TIME." minutes"))
			->format(TokenManager::date_format);
		
		return $this->toString([TokenManager::limit_key => $limit]);
	}

	private function validHeader(array $header) : bool {
		if ($header == null || sizeof($header) == 0) {
			return false;
		}

		if (!isset($header[TokenManager::limit_key])) {
			return false;
		}

		$limit = $header[TokenManager::limit_key];

		if ($limit == null || strlen($limit) == 0) {
			return false;
		}

		$limit = DateTime::createFromFormat(TokenManager::date_format, $limit);

		if ((new DateTime()) > $limit) {
			return false;
		}

		return true;
	}

	private function validContent(array $content) : bool {
		if ($content == null || sizeof($content) == 0) {
			return false;
		}
	
		foreach ($content as $key => $value) {
			if (!is_string($key) || strlen($key) == 0 || !is_string($value) || strlen($value) == 0) {
				return false;
			}
		}

		return true;
	}

	private function toString(object|array $obj) : string {
		if ($obj == null || (!is_array($obj) && !is_object($obj)) || sizeof($obj) == 0) {
			return null;
		}

		$obj = is_array($obj)
			? $obj
			: get_object_vars($obj);

		$str = "";
		foreach ($obj as $key => $value) {
			if (strlen($key) == 0 || strlen($value) == 0) {
				continue;
			}

			$str .= (strlen($str) != 0 ? ";" : "") . "$key=$value";
		}

		return $str;
	}

	private function toMap(string $value) : ?array {
		if ($value == null || !is_string($value) || strlen($value) == 0) {
			return null;
		}

		try {
			$innerVals = preg_split("/\;/", $value);
			foreach ($innerVals as $val) {
				$tmp = preg_split("/\=/", $val);
				$value[$tmp[0]] = $tmp[1];
			}

			return $value;
		} catch (Throwable $th) {
			$this->logger->log("Cannot map");
			$this->logger->log($th);

			return null;
		}
	}
}