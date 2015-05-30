<?php

namespace Glad\Interfaces;

use SessionHandlerInterface;
use Glad\Interfaces\CryptInterface;

interface GladSessionHandlerInterface extends SessionHandlerInterface {

	public function openSession(array $config, CryptInterface $crypt);

}