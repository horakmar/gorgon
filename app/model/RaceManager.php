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

	public function getCourse($courseid) {
		$course_table = $this->raceid . "__course";
		$row = $this->database->table($course_table)->get($courseid);
		return $row->toArray();
	}
	
	public function getCourseCP($courseid) {
		$cp_table = $this->raceid . "__course_cp";
		$table = $this->database->table($cp_table)->where('course_id', $courseid)->order('cptype, sequence');
		$values = [];
		foreach($table as $row){
			$values[] = $row->toArray();
		}
		return $values;
	}

	public function putCourse($courseid, $course_val, $cp_val) {
		$course_table = $this->raceid . "__course";
		$cp_table = $this->raceid . "__course_cp";

		if($courseid){
			$this->database->table($course_table)->get($courseid)->update($course_val);
			$this->database->table($cp_table)->where('course_id', $courseid)->delete();
		}else{
			$row = $this->database->table($course_table)->insert($course_val);
			$courseid = $row->getPrimary();
		}
		$cp_course = []; $seq = 0;
		foreach($cp_val['cpcode'] as $key => $val){
			if($key == 0) continue; 		// skip template value
			if($val != ''){
				if($cp_val['cptype'][$key] == 'regular'){
					$cpseq = ++$seq;
				}else{
					$cpseq = 0;
				}
				$cp_course[] = ['cpcode' => $val, 'sequence' => $cpseq,
				  'course_id' => $courseid,
				  'cptype' => $cp_val['cptype'][$key],
				  'cpsect' => $cp_val['cpsect'][$key],
				  'cpchange' => $cp_val['cpchange'][$key],
				  'cpdata' => $cp_val['cpdata'][$key]];
			}
		}
		$this->database->table($cp_table)->insert($cp_course);
	}
	
	public function delCourse($courseid) {
		$course_table = $this->raceid . "__course";
		$cp_table = $this->raceid . "__course_cp";

		$this->database->table($cp_table)->where('course_id', $courseid)->delete();
		$this->database->table($course_table)->get($courseid)->delete();
	}
}
