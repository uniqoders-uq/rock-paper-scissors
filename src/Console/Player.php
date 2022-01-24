<?php

namespace Uniqoders\Game\Console;

use Uniqoders\Game\Contracts\PlayerInterface;

class Player implements PlayerInterface
{
    public int $weapon;
    private string $name;
    public Stat $stats;
    public function __construct($name)
    {
        $this->setName($name);
        $this->stats = new Stat();
    }

    public function setWeapon(int $weapon): void
    {
        $this->weapon = $weapon;
    }
    public function getWeapon(): int
    {
        return $this->weapon;
    }
    public function setName(string $name): void
    {
        $this->name = $name;
    }
    public function getName(): string
    {
        return $this->name;
    }
}
