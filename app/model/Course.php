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
	private $crs_table;

	/** @var Nette\Database\Table\Selection */
	private $cp_table;

	/** @var array */
	public $course = [];
	public $course_cp = [];

	public function __construct(Nette\Database\Context $database)
	{
		$this->database = $database;
	}

	public function setCourse($raceid = NULL, $courseid = NULL) {
		$this->cp_table = $this->database->table($raceid . self::CP_TABLE_SUFF);
		$this->crs_table = $this->database->table($raceid . self::CRS_TABLE_SUFF);
		$this->course['id'] = $courseid;
	}

	public function load($courseid) {
		$this->course = $this->crs_table->get($courseid)->toArray();
		$result = $this->cp_table->where('course_id', $courseid)->order('cptype, sequence');
		foreach($result as $row){
			$this->course_cp[] = $row->toArray();
		}
		return $this;
	}

	public function create($courseid, $course_val, $cp_val){
		$this->course = $course_val;
		$this->course['id'] = $courseid;
		
		$seq = 0;
		foreach($cp_val['cpcode'] as $key => $val){
			if($key == 0) continue; 		// skip template value
			if($val != ''){
				if($cp_val['cptype'][$key] == 'regular'){
					$cpseq = ++$seq;
				}else{
					$cpseq = 0;
				}
				$this->course_cp[] = ['cpcode' => $val, 'sequence' => $cpseq,
				  'course_id' => $courseid,
				  'cptype' => $cp_val['cptype'][$key],
				  'cpsect' => $cp_val['cpsect'][$key],
				  'cpchange' => $cp_val['cpchange'][$key],
				  'cpdata' => $cp_val['cpdata'][$key]];
			}
		}
	}

	public function save() {
		if($this->course['id']){
			$this->crs_table->get($this->course['id'])->update($this->course);
			$this->cp_table->where('course_id', $this->course['id'])->delete();
		}else{
			unset($this->course['id']);
			$this->course['id'] = $this->crs_table->insert($this->course)->getPrimary();
		}
		foreach($this->course_cp as &$cp){
			$cp['course_id'] = $this->course['id'];
		}
		$this->cp_table->insert($this->course_cp);
	}
	
	public function delete() {
		$this->cp_table->where('course_id', $this->course['id'])->delete();
		$this->crs_table->get($this->course['id'])->delete();
	}
}
