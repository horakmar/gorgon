<?php

namespace App\Model;

use Nette;
use Tracy\Debugger;


/**
 * Users management.
 */
class Race extends BaseModel {

	const MAIN_TABLE = 'g__race';
	const RACE_TABLES = 'best_splits course course_cp category entry read read_punch results splits';
	const TEMPLATE_PREFIX = 't';

	const TYPE_TRAINING = 1,
		  TYPE_RACE = 2,
		  KIND_REGULAR = 1,
		  KIND_FREEO = 2,
		  INCO_LAST = 1,
		  INCO_DISK = 2,
		  INCO_PENA = 3;

	public function addRace($values) {
		foreach(explode(' ', self::RACE_TABLES) as $tb){
			$racetb = $values->raceid . '__' . $tb;
			$templatetb = self::TEMPLATE_PREFIX . '__' . $tb;
			switch($tb){
			case 'entry' :
				$constraints = "ADD FOREIGN KEY (`category_id`) REFERENCES `gorgon_1`.`{$values->raceid}__category`(`id`) ON DELETE SET NULL ON UPDATE RESTRICT";
				break;
			case 'category' :
				$constraints = "ADD FOREIGN KEY (`course_id`) REFERENCES `gorgon_1`.`{$values->raceid}__course`(`id`) ON DELETE SET NULL ON UPDATE RESTRICT";
				break;
			case 'course_cp' :
				$constraints = "ADD FOREIGN KEY (`course_id`) REFERENCES `gorgon_1`.`{$values->raceid}__course`(`id`) ON DELETE CASCADE ON UPDATE RESTRICT";
				break;
			case 'read_punch' :
				$constraints = "ADD FOREIGN KEY (`read_id`) REFERENCES `gorgon_1`.`{$values->raceid}__read`(`id`) ON DELETE CASCADE ON UPDATE RESTRICT";
				break;
			case 'results_punch' :
				$constraints = "ADD FOREIGN KEY (`results_id`) REFERENCES `gorgon_1`.`{$values->raceid}__results`(`id`) ON DELETE CASCADE ON UPDATE RESTRICT";
				break;
			default:
				$constraints = '';
			}
			$this->database->query("CREATE TABLE $racetb LIKE $templatetb");
			if($constraints){
				$this->database->query("ALTER TABLE $racetb $constraints");
			}
		}
		$this->database->table(self::MAIN_TABLE)->insert($values);
/*
		$this->database->table(self::MAIN_TABLE)->insert([
			'raceid' => $values->raceid,
			'name' => $values->name,
			'datetime_0' => $values->datetime_0,
			'type' => $values->type,
			'descr' => $values->descr
			
		]);
 */
	}

	public function editRace($values) {
		if(array_key_exists('raceid', $values)) {
			unset($values['raceid']);
		}
		$this->database->table(self::MAIN_TABLE)->where('raceid', $this->raceid)->update($values);
	}

	public function delRace($raceid) {
		$sql = 'DROP TABLE ';
		foreach(array_reverse(explode(' ', self::RACE_TABLES)) as $tb){
			$sql .= "`{$raceid}__$tb`, ";
		}
		$sql = substr($sql, 0, -2);
		$this->database->query($sql);
		$this->database->table(self::MAIN_TABLE)->where('raceid', $this->raceid)->delete();
	}

	public function freeRaceid($raceid) {
		return ($this->database->table(self::MAIN_TABLE)
			->where('raceid', $raceid)->count() == 0);
	}

	public function listAll() {
		return $this->database->table(self::MAIN_TABLE);
	}

	public function load($raceid) {
		return $this->listAll()->where('raceid', $raceid)->fetch();
	}

	public function getRaceInfo() {
		$raceinfo = $this->listAll()->where('raceid', $this->raceid)->fetch()->toArray();
		$raceinfo['time_0'] = self::time2sec($raceinfo['datetime_0']);
		return $raceinfo;
	}

	public function listCourses() {
		return $this->database->table($this->raceid . parent::COURSE_TABLE_SUFF);
	}

	public function listCategories() {
		return $this->database->table($this->raceid . parent::CATEGORY_TABLE_SUFF);
	}

	public function listEntries() {
		return $this->database->table($this->raceid . parent::ENTRY_TABLE_SUFF);
	}

}
