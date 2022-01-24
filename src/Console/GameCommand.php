<?php

namespace Uniqoders\Game\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Uniqoders\Game\Contracts\CoreGameInterface;
use Uniqoders\Game\Contracts\GameModeInterface;
use Uniqoders\Game\Contracts\RoundInterface;

class GameCommand extends Command implements CoreGameInterface
{
    /**
     * Configure the command options.
     *
     * @return void
     */
    public array $players;
    public array $weapons;
    public Player $player1;
    public Player $player2;
    public array $rules;
    public int $round;
    public int $max_round;
    public int $no_return;
    private InputInterface $input;
    private $output;
    private mixed $ask;
    private $gamemodes = [
        0 => 'Classic',
        1 => 'Plus Spock Lizard'
    ];
    private GameModeInterface $gameMode;


    protected function configure()
    {
        $this->setName('game')
            ->setDescription('New game: you vs computer')
            ->addArgument('name', InputArgument::OPTIONAL, 'what is your name?', 'Player 1');
    }

    public function setPlayers()
    {
        $this->player1 = new Player($this->playerName);
        $this->player2 = new Player('Computer');
        $this->players = [
            'player' => $this->player1,
            'computer' => $this->player2
        ];
        return $this;
    }
    public function setGameMode()
    {
        // Choose Game Mode
        $this->ask = $this->getHelper('question');
        $question0 = new ChoiceQuestion('Game Mode', array_values($this->gamemodes), 1);
        $question0->setErrorMessage('Mode %s is invalid.');
        $gameMode = $this->ask->ask($this->input, $this->output, $question0);
        $this->output->writeln('Game Mode: ' . $gameMode);
        $this->gameMode = (array_search($gameMode, $this->gamemodes) == 0) ? new ClassicMode : new SpockLizardMode;
        return $this;
    }
    public function setRules(GameModeInterface $gameMode)
    {
        /**
         * Can Set rules from an outside GameModeInterface 
         */
        $this->rules = $gameMode->getRules();
        $this->round = 1;
        $this->max_round = $gameMode->getRounds();
        // $no_return var: This ensure game to stop when looser has not more opportunities for win or draw
        $this->no_return = intval($this->max_round / 2) + 1;
        return $this;
    }
    public function setWeapons()
    {
        /**
         * Set which weapons can use player
         */
        $this->weapons =  $this->gameMode->getWeapons();
        return $this;
    }
    public function play()
    {
        /**
         * This are the rounds played during the game
         */
        $fireRound = new Round($this->player1, $this->player2);
        $fireRound->setRules($this->rules);
        do {
            // User selection
            $question = new ChoiceQuestion('Please select your weapon', array_values($this->weapons), 1);
            $question->setErrorMessage('Weapon %s is invalid.');

            $user_weapon = $this->ask->ask($this->input, $this->output, $question);
            $this->output->writeln('You have just selected: ' . $user_weapon);

            $this->player1->setWeapon(array_search($user_weapon, $this->weapons));

            // Computer selection
            $this->player2->setWeapon(array_rand($this->weapons));
            $this->output->writeln('Computer has just selected: ' . $this->weapons[$this->player2->getWeapon()]);

            $resultRound = $fireRound->fireRound();
            if ($resultRound === null) {
                /**
                 * When draw:
                 * $no_return var: This ensure game to end when looser has not more opportunities for win or draw, 
                 * and avoid non-sense rounds
                 * This var is affected by each draw, so it need to be recalculated
                 * 
                 */

                $this->no_return = intval(($this->max_round - $this->player1->stats->getStat('draw')) / 2) + 1;

                $this->output->writeln('Draw!');
            } else {
                $this->output->writeln($resultRound->getName() . ' win!');
            }

            if (
                $this->player2->stats->getStat('victory') >= $this->no_return
                || $this->player1->stats->getStat('victory') >= $this->no_return
            ) {
                break;
            }

            $this->round++;
        } while (
            $this->round <= $this->max_round
            && ($this->player2->stats->getStat('victory') >= $this->no_return
                || $this->player1->stats->getStat('victory') >= $this->no_return) == false
        );
        return $this;
    }
    public function showResults()
    {
        // Display stats

        $stats = $this->players;

        $stats = array_map(function (Player $player) {
            return [
                $player->getName(),
                $player->stats->getStat('victory'),
                $player->stats->getStat('draw'),
                $player->stats->getStat('defeat')
            ];
        }, $stats);

        $table = new Table($this->output);
        $table
            ->setHeaders(['Player', 'Victory', 'Draw', 'Defeat'])
            ->setRows($stats);

        $table->render();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->write(PHP_EOL . 'Made with ♥ by Uniqoders.' . PHP_EOL . PHP_EOL);
        $this->input = $input;
        $this->output = $output;
        $this->playerName = $input->getArgument('name');
        // Choose Game Mode
        $this->setGameMode();

        // Override Rounds
        //$this->gameMode->setRounds(5);

        // Go for game
        $this
            ->setPlayers()
            ->setRules($this->gameMode)
            ->setWeapons()
            ->play()
            ->showResults();

        return 0;
    }
}
