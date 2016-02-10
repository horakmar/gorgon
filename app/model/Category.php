<?php

namespace App\Model;

use Tracy\Debugger;


/**
 * Category management.
 */
class Category extends BaseModel {

	const STORD_LIST_SI = 1,
		  STORD_SI_LIST = 2,
		  STORD_LIST = 3,
		  STORD_SI = 4;

	/** @var Nette\Database\Table\ActiveRow */
	private $category;

	public function listAll() {
		return $this->database->table($this->raceid . parent::CATEGORY_TABLE_SUFF);
	}

	public function load($catid) {
		return $this->listAll()->get($catid);
	}

	public function insert($cat_val){
		$insert_val = [];
		foreach($cat_val['name'] as $key => $val){
			if($val != ''){
				$insert_val[] =  [
					'name' => $val,
				    'course_id' => $cat_val['course_id'][$key]
				 ];
			}
		}
		$this->listAll()->insert($insert_val);
	}

	public function update($catid, $values){
		if($catid){
			$this->listAll()->get($catid)->update($values);
		}else{
			$this->listAll()->insert($values);
		}
	}

	public function countReferences($catid){
		return $this->load($catid)
			->related($this->raceid . parent::ENTRY_TABLE_SUFF, 'category_id')->count();
	}

	public function delete($catid) {
		$this->load($catid)->delete();
	}
}
