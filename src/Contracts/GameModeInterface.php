<?php

namespace Uniqoders\Game\Contracts;

interface GameModeInterface
{
    public function setWeapons(array $weapons);
    public function setRules(array $rules);
    public function setRounds(int $rounds);
    public function getWeapons(): array;
    public function getRules(): array;
    public function getRounds(): int;
}
