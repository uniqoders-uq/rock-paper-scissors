<?php

namespace Uniqoders\Game\Contracts;

interface StatInterface{
    public function getStats(): array;
    public function getStat(string $stat);
    public function drawUp():void;
    public function victoryUp():void;
    public function defeatUp():void;
}