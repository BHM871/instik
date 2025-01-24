<?php

use System\Logger;

class FileService {

	private readonly Logger $logger;

	public function __construct() {
		$this->logger = new Logger($this);		
	}

	public function upload(array $file, ?string $filename = null, string $path = DEFAULT_UPLOADS_PATH) : ?string {
		if ($file == null || sizeof($file) == 0 || $file['error'] != 0 || $file['size'] <= 0)
			return null;
		
		if (!isset($file['tmp_name']) || !isset($file['name']) || !isset($file['size']) || $file['size'] <= 0)
			return null;

		if (!file_exists($path))
			mkdir($path, 0777, true);

		$filename = $filename != null
			? $filename
			: $file['name'];

		$filepath = preg_match("/[^\/]$/", $path) ? "$path/" : $path;
		$filepath .= basename($filename);

		try {
			move_uploaded_file($file['tmp_name'], $filepath);

			return $filepath;
		} catch (Throwable $th) {
			$this->logger->log($th);

			return null;
		}
	}
}