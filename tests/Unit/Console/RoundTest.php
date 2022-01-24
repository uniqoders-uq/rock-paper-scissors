<?php 

namespace Uniqoders\Tests\Unit\Console;

use Uniqoders\Game\Console\Player;
use Uniqoders\Game\Console\Contracts\PlayerInterface;
use Uniqoders\Game\Console\Round;
use Uniqoders\Tests\Unit\UnitTestCase;

class RoundTest extends UnitTestCase
{
    /**
     * Test for rounds player1 vs player2
     * Win and draw
     * 
     */
    public function test_round_player1win(){
        $player1 = new Player('Player 1');
        $player2 = new Player('Computer');
        $round = new Round($player1,$player2);
        $round->setRules([0=>[1]]);
        $player1->setWeapon(0);
        $player2->setWeapon(1);
        $this->assertSame($player1,$round->fireRound());
        
    }
    public function test_round_win_player2win(){
        $player1 = new Player('Player 1');
        $player2 = new Player('Computer');
        $round = new Round($player1,$player2);
        $round->setRules([0=>[2],1=>[0]]);
        $player1->setWeapon(0);
        $player2->setWeapon(1);
        $this->assertSame($player2,$round->fireRound());
        
    }
    public function test_round_draw(){
        $player1 = new Player('Player 1');
        $player2 = new Player('Computer');
        $round = new Round($player1,$player2);
        $round->setRules([0=>[1]]);
        $player1->setWeapon(0);
        $player2->setWeapon(0);

        $this->assertNull($round->fireRound());
    }
    
    
}