<?php

/**
 * @author Hemerson Marquez <hlmarquez@gmail.com>
 * 
 * Clase para gestionar un enfrentamiento, con la lógica del juego "Piedra, Papel y Tijera",
 * pero expandible con muchas mas opciones de armas.
 */

namespace Uniqoders\Game\Classes;

use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Output\ConsoleOutput;
use Uniqoders\Game\Classes\Player;

class MatchGame
{

	const MAX_MATCHES = 5;

	private $weapons;
	private $rules;
	private $players = [];
	private $errors = [];
	private $playing = false;
	private $matches;
// ==================================================================================
/**
 * @param Array $weapons Conjunto de armas a enfrentar.
 * @param Array $rules Conjunto de reglas de enfrentamiento, key es el ganador y el valor el/los perdedores.
*/
	function __construct(Array $weapons, Array $rules)
	{
		$this->weapons = $weapons;
		$this->rules = $rules;
	}

// =============================== PRIVATE FUNCTIONS ================================
// ==================================================================================
/**
 * Muestra un mensaje por consola
 * @param String $message mensaje a mostrar
*/
	private function consoleMessage(String $message)
	{
		$output = new ConsoleOutput();
		$output->writeln($message);
	}

// ==================================================================================
/**
 * Define qué lado es el ganador entre los enfrentamientos, con reglas de indice ganador y valores como perdedores
 * @param String $a selección de lado A
 * @param String $b selección de lado B
 * @return Int
 * 	0: empate
 * 	1: jugador A gana
 * 	2: jugador B gana
 * 	3: Regla no definida
*/
	private function defineWinner($a, $b)
	{
		if($a == $b) return 0;

		if( isset($this->rules[$a]) && ($this->rules[$a] == $b || in_array($b, is_array($this->rules[$a]) ? $this->rules[$a] : [])) )
			return 1;


		if( isset($this->rules[$b]) && ($this->rules[$b] == $a || in_array($a, is_array($this->rules[$b]) ? $this->rules[$b] : [])) )
			return 2;

		return 3;
	}

// ==================================================================================
/**
 * Muestra la tabla final de resultados.
*/
	private function showTableResults()
	{
		$output = new ConsoleOutput();
		$lines = [];

		foreach ($this->players as $player)
			$lines[] = $player->getLineResults();

		$table = new Table($output);
		$table->setHeaders(['Jugador', 'victorias', 'empates', 'derrotas'])
		->setRows($lines);

		$table->render();
	}

// =============================== PUBLIC FUNCTIONS =================================
// ==================================================================================
/**
 * Agrega un jugador con datos definidos.
 * @param Player $player Objeto instancia de la clase Uniqoders\Game\Classes\Player
 * @return Bool Informa si fue agregado el jugador o no por límite de jugadores.
*/
	public function addPlayer(Player $player)
	{
		// se define un máximo de 2 jugadores para una definición de enfrentamiento sencillo.
		if(count($this->players) > 1) return false;

		$this->players[] = $player;
		return true;
	}

// ==================================================================================
/**
 * Inicia el conjunto de partidas definidas por el $matches con muestra final de resultados.
 * @param Int $matches cantidad de enfrentamientos a realizar.
*/
	public function Play(Int $matches = 5)
	{
		if($matches > self::MAX_MATCHES) return "Máximo de ".self::MAX_MATCHES." partidas a realizar y no {$matches}.";
		if(!$this->weapons) return "Sin armas suficientes.";

		while( count($this->players) < 2 ) $this->players[] = new Player('', true);

		for($games = 0; $games < $matches; $games++)
		{
			$this->players[0]->doPlay($this->weapons);
			$this->consoleMessage($this->players[0]->getName()." ha seleccionado: ".$this->players[0]->selected());

			$this->players[1]->doPlay($this->weapons);
			$this->consoleMessage($this->players[1]->getName()." ha seleccionado: ".$this->players[1]->selected());

			$matchResult = $this->defineWinner( $this->players[0]->selected(), $this->players[1]->selected() );
			switch ($matchResult)
			{
				case 0:
					$this->players[0]->addDraw();
					$this->players[1]->addDraw();
					$this->consoleMessage("Empate!!".PHP_EOL);
					break;

				case 1:
					$this->players[0]->addVictory();
					$this->players[1]->addDefeat();
					$this->consoleMessage($this->players[0]->getName()." Ganó!!".PHP_EOL);
					break;

				case 2:
					$this->players[0]->addDefeat();
					$this->players[1]->addVictory();
					$this->consoleMessage($this->players[1]->getName()." Ganó!!".PHP_EOL);
					break;

				default:
					$this->consoleMessage("<< Regla de enfrentamiento no definida >>".PHP_EOL);
					break;
			}

			if($this->players[0]->getVictories() > 2 || $this->players[1]->getVictories() > 2)
				break;
		}

		$this->showTableResults();
		return true;
	}

// ==================================================================================

}
