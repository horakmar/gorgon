<?php

namespace App\Model;

use Nette;
use Tracy\Debugger;


/**
 * Users management.
 */
class RaceManager extends Nette\Object {

	const MAIN_TABLE = 'g__race';
	const RACE_TABLES = 'best_splits category course course_cp entry read read_punch results splits';
	const TEMPLATE_PREFIX = 't';

	/** @var Nette\Database\Context */
	private $database;

	/** @var raceid */
	private $raceid;

	public function __construct(Nette\Database\Context $database)
	{
		$this->database = $database;
	}

	public function setRaceID($raceid = NULL) {
		$this->raceid = $raceid;
	}

	public function addRace($values) {
		foreach(explode(' ', self::RACE_TABLES) as $tb){
			$racetb = $values->raceid . '__' . $tb;
			$templatetb = self::TEMPLATE_PREFIX . '__' . $tb;
			$this->database->query("CREATE TABLE $racetb LIKE $templatetb");
		}
		$this->database->table(self::MAIN_TABLE)->insert(array(
			'raceid' => $values->raceid,
			'name' => $values->name,
			'datetime_0' => $values->datetime_0,
			'type' => $values->type,
			'descr' => $values->descr
		));
	}

	public function freeRaceid($raceid) {
		return ($this->database->table(self::MAIN_TABLE)
			->where('raceid', $raceid)->count() == 0);
	}

	public function listRaces() {
		return $this->database->table(self::MAIN_TABLE);
	}

	public function getRaceInfo() {
		$race = $this->database->table(self::MAIN_TABLE)
			->where('raceid', $this->raceid)->fetch();
		return $race;
	}

	public function listCourses() {
		return $this->database->table($this->raceid . '__course');
	}

	public function listCategories() {
		return $this->database->table($this->raceid . '__category');
	}

	public function listEntries() {
		return $this->database->table($this->raceid . '__entry');
	}

}
