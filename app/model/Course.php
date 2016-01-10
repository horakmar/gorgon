<?php

namespace App\Model;

use Tracy\Debugger;


/**
 * Users management.
 */
class Course extends BaseModel {

	public function listAll() {
		return $this->database->table($this->raceid . parent::COURSE_TABLE_SUFF);
	}

	public function load($courseid) {
		return $this->listAll()->get($courseid);
	}

	public function loadCPs($course) {
		return $course->related($this->raceid . parent::CP_TABLE_SUFF, 'course_id')->order('cptype, sequence');
	}

	public function save($courseid, $course_val, $cp_val){
		if($courseid){
			$course = $this->load($courseid);
			$this->loadCPs($course)->delete();
			$course->update($course_val);
		}else{
			$courseid = $this->listAll()->insert($course_val)->getPrimary();
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
		if(count($course_cp) > 0){
			$this->database->table($this->raceid . parent::CP_TABLE_SUFF)->insert($course_cp);
		}
	}

	public function delete($courseid) {
		$this->load($courseid)->delete();
	}
}
