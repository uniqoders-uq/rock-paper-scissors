<?php

namespace Uniqoders\Game\Console;

use Uniqoders\Game\Contracts\GameModeInterface;

class ClassicMode implements GameModeInterface
{
    /**
     * In this mode values are:
     * 
     * $weapons = Scissors, Rock, Paper
     * $rounds = 5
     * 
     */

    public $weapons = [
        0 => 'Scissors',
        1 => 'Rock',
        2 => 'Paper'
    ];
    public $rules =  [
        0 => [2],
        1 => [0],
        2 => [1]
    ];
    public $rounds = 5;


    public function setWeapons(array $weapons = [])
    {
        if ($weapons != []) {
            $this->weapons = $weapons;
        }
        return $this;
    }
    public function setRules(array $rules = [])
    {
        if ($rules != []) {
            $this->rules = $rules;
        }
        return $this;
    }
    public function setRounds(int $rounds)
    {
        $this->rounds = $rounds;
        return $this;
    }
    public function getWeapons(): array
    {
        return $this->weapons;
    }
    public function getRules(): array
    {
        return $this->rules;
    }
    public function getRounds(): int
    {
        return $this->rounds;
    }
}
