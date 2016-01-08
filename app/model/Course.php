<?php

namespace App\Model;

use Nette;
use Tracy\Debugger;


/**
 * Users management.
 */
class Course extends Nette\Object {

	const CRS_TABLE_SUFF = '__course';
	const CP_TABLE_SUFF = '__course_cp';

	/** @var Nette\Database\Context */
	private $database;

	/** @var Nette\Database\Table\Selection */
	private $crs_table, $cp_table;

	/** @var string */
	private $cp_table_name;

	/** @var Nette\Database\Table\ActiveRow */
	public $course;

	/** @var Nette\Database\Table\GroupedSelection */
	public $course_cp;

	public function __construct(Nette\Database\Context $database)
	{
		$this->database = $database;
	}

	public function setCourse($raceid = NULL) {
		$this->cp_table_name = $raceid . self::CP_TABLE_SUFF;
		$this->cp_table = $this->database->table($this->cp_table_name);
		$this->crs_table = $this->database->table($raceid . self::CRS_TABLE_SUFF);
	}

	public function load($courseid) {
		$this->course = $this->crs_table->get($courseid);
		$this->course_cp = $this->course->related($this->cp_table_name, 'course_id')->order('cptype, sequence');
		return $this;
	}

	public function save($courseid, $course_val, $cp_val){
		if($courseid){
			$this->crs_table->get($courseid)->update($course_val);
			$this->cp_table->where('course_id', $courseid)->delete();
		}else{
			$courseid = $this->crs_table->insert($course_val)->getPrimary();
		}
		$seq = 0;
		$course_cp = [];
		foreach($cp_val['cpcode'] as $key => $val){
			if($key == 0) continue; 		// skip template value
			if($val != ''){
				if($cp_val['cptype'][$key] == 'regular'){
					$cpseq = ++$seq;
				}else{
					$cpseq = 0;
				}
				$course_cp[] = ['cpcode' => $val, 'sequence' => $cpseq,
				  'course_id' => $courseid,
				  'cptype' => $cp_val['cptype'][$key],
				  'cpsect' => $cp_val['cpsect'][$key],
				  'cpchange' => $cp_val['cpchange'][$key],
				  'cpdata' => $cp_val['cpdata'][$key]];
			}
		}
		$this->cp_table->insert($course_cp);
	}

	public function delete($courseid) {
		$this->crs_table->get($courseid)->delete();
	}
}
