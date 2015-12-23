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


	public function __construct(Nette\Database\Context $database)
	{
		$this->database = $database;
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

	public function getRaceInfo($raceid) {
		$race = $this->database->table(self::MAIN_TABLE)
			->where('raceid', $raceid)->fetch();
		return $race;
	}

	public function listCourses($raceid) {
		return $this->database->table($raceid . '__course');
	}

	public function listCategories($raceid) {
		return $this->database->table($raceid . '__category');
	}

	public function listEntries($raceid) {
		return $this->database->table($raceid . '__entry');
	}

	public function putCourse($raceid, $course_val, $cp_val) {
		$course_table = $raceid . "__course";
		$cp_table = $raceid . "__course_cp";
		$row = $this->database->table($course_table)->insert($course_val);
		$courseid = $row->getPrimary();
		$cp_course = []; $seq = 0;
		foreach($cp_val['CPcode'] as $key => $val){
			if($val != ''){
				$section = ($cp_val['CPsect'][$key] == '') ? 1 : $cp_val['CPsect'][$key];
				if($cp_val['CPtype'][$key] == 'regular'){
					$cp_seq= ++$seq;
				}else{   // freeeo
					$cp_seq = 0;
				}
				$cp_course[] = ['code' => $val, 'course_id' => $courseid, 'sequence' => $cp_seq, 'section' => $section, 'type' => $cp_val['CPtype'][$key]];
			}
		}
		Debugger::bardump($cp_course);
		$this->database->table($cp_table)->insert($cp_course);
	}
}
