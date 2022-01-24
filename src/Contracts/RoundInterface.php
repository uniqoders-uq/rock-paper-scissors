<?php

namespace Uniqoders\Game\Contracts;

use Uniqoders\Game\Console\Player;

interface RoundInterface
{
    public function setRules(array $rules): void;
    public function fireRound(): ? Player;
}
