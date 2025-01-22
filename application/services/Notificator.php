<?php

namespace Instik\Services;

use Instik\Gateways\EmailFacade;
use Instik\Util\EmailTemplate;

class Notificator {

	public function __construct(
		private readonly EmailFacade $emailSender
	){}

	public function changePassword(string $email, string $hash) : bool {
		return $this->emailSender->sendEmail([$email], "Trocar Senha", EmailTemplate::changePassword($hash));
	}

}