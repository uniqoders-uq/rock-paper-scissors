<?php

namespace Uniqoders\Game\Console;

use Uniqoders\Game\Contracts\RoundInterface;
use Uniqoders\Game\Console\Player;


class Round implements RoundInterface
{
    public array $rules;
    public array $players;
    public Player $player1;
    public Player $player2;



    public function __construct(Player $player1, Player $player2)
    {
        $this->player1 = $player1;
        $this->player2 = $player2;
    }
    public function setRules(array $rules): void
    {
        $this->rules = $rules;
    }
    public function fireRound(): ?Player
    {

        if (in_array($this->player2->getWeapon(), $this->rules[$this->player1->getWeapon()])) {
            $this->player1->stats->victoryUp();
            $this->player2->stats->defeatUp();
            return $this->player1;
        } else if (in_array($this->player1->getWeapon(), $this->rules[$this->player2->getWeapon()])) {
            $this->player1->stats->defeatUp();
            $this->player2->stats->victoryUp();
            return $this->player2;
        } else {
            $this->player1->stats->drawUp();
            $this->player2->stats->drawUp();

            return null;
        }
    }
}
