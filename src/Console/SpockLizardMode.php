<?php

namespace Uniqoders\Game\Console;

class SpockLizardMode extends ClassicMode
{

    public function __construct()
    {
        /**
         * In this mode values are:
         * 
         * $weapons = Scissors, Rock, Paper, Lizard, Spock
         * $rounds = 7
         * 
         */
        $this->setRules([
            0 => [2, 3],
            1 => [0, 3],
            2 => [1, 4],
            3 => [2, 4],
            4 => [0, 1],
        ]);
        $this->setWeapons([
            0 => 'Scissors',
            1 => 'Rock',
            2 => 'Paper',
            3 => 'Lizard',
            4 => 'Spock'
        ]);
        $this->setRounds(7);
    }
}
