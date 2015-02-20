<?php

namespace Glad;

interface ConditionsInterface {

	public function apply(array $conditions, array $user);

}