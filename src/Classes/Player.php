<?php

/**
 * @author Hemerson Marquez <hlmarquezm@gmail.com>
 * 
 * Clase de interacción en acciones como jugador para el juego de piedra, papel y tijeras
 */

namespace Uniqoders\Game\Classes;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\ConsoleOutput;


class Player
{
	private $name;
	private $bot;
	private $lastSelected;
	private $results = [
		'draw' => 0,
		'victory' => 0,
		'defeat' => 0,
	];

// ==================================================================================
/**
* @param String $name Nombre del Jugador a generar.
* @param Boolean $bot Epeficia si el Jugadr actuara como un bot, de selección automática.
*/
	function __construct(String $name = '', Bool $bot = false)
	{
		if(!trim($name)) $name = 'player_'.hash('crc32',mt_rand());

		$this->name = $name;
		$this->bot = $bot; 
	}

// =============================== PRIVATE FUNCTIONS ================================
// ==================================================================================
/**
 * Genera la interacción para pedir la selección por parte del usuario
 * @param Array $weapons Conjunto de armas definidas para el juego.
 * @return String retorna el valor de la opción seleccionada.
*/
	private function consoleAsk(Array $weapons)
	{
		$question = new QuestionHelper();

		$input = new ArgvInput();
		$output = new ConsoleOutput();

		$choices = new ChoiceQuestion("Por favor {$this->name}, Seleccione el arma", array_values($weapons), 1);
		$choices->setErrorMessage('Arma %s, no es válida.');

		return $question->ask($input, $output, $choices);
	}

// =============================== PUBLIC FUNCTIONS =================================
// ==================================================================================
/**
 * Retorna el nombre definido del jugador.
 * @return String
*/
	public function getName()
	{
		return $this->name;
	}

// ==================================================================================
/**
 * Retorna si es un bot, de selección multiple.
 * @return Bool
*/
	public function isBot()
	{
		return $this->bot;
	}

// ==================================================================================
/**
 * Realiza la acción de seleccionar la opción según el tipo de usuario.
 * @param $weapons Conjunto de armas del juego.
 * @return String
*/
	public function doPlay(Array $weapons)
	{

			$this->lastSelected =
				$this->bot
				? $weapons[ floor((1.0*mt_rand()/mt_getrandmax()) * count($weapons)) ]
				: $this->consoleAsk($weapons);

			return $this->lastSelected;
	}
// ==================================================================================
/**
 * Adiciona un 1 el registro de empates.
*/
	public function addDraw()
	{
		$this->results['draw']++;
	}

// ==================================================================================
/**
 * Adiciona un 1 el registro de victorias.
*/
	public function addVictory()
	{
		$this->results['victory']++;
	}

// ==================================================================================
/**
 * Adiciona un 1 el registro de derrotas.
*/
	public function addDefeat()
	{
		$this->results['defeat']++;
	}

// ==================================================================================
/**
 * Retorna el valor de la última opción seleccionada.
 * @return String
*/
	public function selected()
	{
		return $this->lastSelected;
	}

// ==================================================================================
/**
 * Retorna el valor de cantidad de victorias realizadas.
 * @return Int
*/
	public function getVictories()
	{
		return $this->results['victory'];
	}

// ==================================================================================
/**
 * Retorna el conjunto de datos organizados para la tabla.
 * @return Array
*/
	public function getLineResults()
	{
		return [
			$this->name,
			$this->results['victory'],
			$this->results['draw'],
			$this->results['defeat'],
		];
	}

// ==================================================================================
}
