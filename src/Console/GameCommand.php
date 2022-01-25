<?php

namespace Uniqoders\Game\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Uniqoders\Game\Classes\MatchGame;
use Uniqoders\Game\Classes\Player;

class GameCommand extends Command
{
    /**
     * Configure the command options.
     *
     * @return void
     */
    protected function configure()
    {
        $this->setName('game')
            ->setDescription('Nuevo Juego: Tú vs Computadora')
            ->addArgument('name', InputArgument::OPTIONAL, '¿Cuál es tu nombre?', 'Jugador');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $weapons = [
            'Piedra',
            'Papel',
            'Tijeras',
        ];

        $rules = [
            'Piedra' => 'Tijeras',
            'Papel' => 'Piedra',
            'Tijeras' => 'Papel',
        ];

        $output->write(PHP_EOL . 'Made with ♥ by Uniqoders.' . PHP_EOL . PHP_EOL);

        $game = new MatchGame($weapons, $rules);

        $game->addPlayer( new Player($input->getArgument('name')) );
        $game->addPlayer( new Player('Computador', true) );

        $status = $game->play(5);

        if(is_string($status))
            $output->write(PHP_EOL.$status.PHP_EOL.PHP_EOL);

        return 0;
    }
}
