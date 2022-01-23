<?php

namespace Uniqoders\Game\Contracts;

interface PlayerInterface
{
    public function setWeapon(int $weapon);
    public function getWeapon(): int;
    public function setName(string $name);
    public function getName(): string;
    
}
