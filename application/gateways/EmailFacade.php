<?php

use PHPMailer\PHPMailer\PHPMailer;

require("./libs/PHPMailer/src/PHPMailer.php");
require("/opt/lampp/phpmyadmin/vendor/autoload.php");

class EmailFacade {

	private ?array $smtp = null;

	private Logger $logger;

	public function __construct() {
		$this->logger = new Logger($this);
	}

	public function sendEmail(array $to, string $subject, string $content, bool $isHtml = true) : bool {
		if ($to == null || sizeof($to) == 0) return false;
		if ($subject == null || $subject == "") return false;
		
		if ($this->smtp == null) $this->getEnv();
		if ($this->smtp == null) return false;

		try {

			$mail = new PHPMailer();

			$mail->isSMTP();
			$mail->Host = 'mail-server';
			$mail->Port = '1025';
			$mail->SMTPAuth = true;
			$mail->Username = $this->smtp['from'];
			$mail->Password = $this->smtp['pass'];

			$mail->From = $this->smtp['from'];
			$mail->FromName = 'Instik';

			foreach ($to as $address) {
				$mail->addAddress($address);
			}

			$mail->isHTML($isHtml);

			$mail->Subject = $subject;
			$mail->Body = $content;

			if($mail->send()) {
				return true;
			}

			$this->logger->log("Cannot send email");
			$this->logger->log($mail->ErrorInfo);

			return false;
		} catch (\Throwable $th) {
			$this->logger->log("Cannot send email");
			$this->logger->log($th);

			return false;
		}
	}

	private function getEnv() {
		if (!isset(env['SMTP_HOST'])) return null;
		if (!isset(env['SMTP_PORT'])) return null;
		if (!isset(env['SMTP_FROM'])) return null;
		if (!isset(env['SMTP_PASS'])) return null;

		$env = [];
		$env['host'] = env['SMTP_HOST'];
		$env['port'] = env['SMTP_PORT'];
		$env['from'] = env['SMTP_FROM'];
		$env['pass'] = env['SMTP_PASS'];

		$this->smtp = $env;
	}

}