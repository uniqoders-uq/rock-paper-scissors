<?php

namespace Uniqoders\Game\Contracts;

use Uniqoders\Game\Console\Player;

interface RoundInterface
{
    public function __construct(Player $player1,Player $player2);
    public function setRules(array $rules): void;
    public function fireRound(): ? Player;
}
