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
			default:
			case 'course_cp' :
				$constraints = "ADD FOREIGN KEY (`course_id`) REFERENCES `gorgon_1`.`{$values->raceid}__course`(`id`) ON DELETE CASCADE ON UPDATE RESTRICT";
				break;
			case 'read_punch' :
				$constraints = "ADD FOREIGN KEY (`read_id`) REFERENCES `gorgon_1`.`{$values->raceid}__read`(`id`) ON DELETE CASCADE ON UPDATE RESTRICT";
				break;
			default:
				$constraints = '';
			}
			$this->database->query("CREATE TABLE $racetb LIKE $templatetb");
			if($constraints){
				$this->database->query("ALTER TABLE $racetb $constraints");
			}
		}
		$this->database->table(self::MAIN_TABLE)->insert([
			'raceid' => $values->raceid,
			'name' => $values->name,
			'datetime_0' => $values->datetime_0,
			'type' => $values->type,
			'descr' => $values->descr
		]);
	}

	public function delRace($raceid){
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

	public function getRaceInfo() {
		return $this->listAll()->where('raceid', $this->raceid)->fetch();
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
