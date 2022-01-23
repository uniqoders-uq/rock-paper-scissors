<?php

namespace Uniqoders\Game\Console;

use Uniqoders\Game\Contracts\StatInterface;

class Stat implements StatInterface
{
    public int $draw = 0;
    private int $victory = 0;
    private int $defeat = 0;


    public function getStat(string $stat)
    {
        return $this->$stat;
    }
    public function getStats(): array
    {
        return [
            'draw' => $this->draw,
            'victory' => $this->victory,
            'defeat' => $this->defeat
        ];
    }
    public function drawUp(): void
    {
        $this->draw++;
    }
    public function victoryUp(): void
    {
        $this->victory++;
    }
    public function defeatUp(): void
    {
        $this->defeat++;
    }
}
