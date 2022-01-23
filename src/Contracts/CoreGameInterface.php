<?php

namespace Uniqoders\Game\Contracts;


interface CoreGameInterface {
    public function setPlayers();
    public function setRules();
    public function setWeapons();
    public function play();
    public function showResults();
}