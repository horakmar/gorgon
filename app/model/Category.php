<?php

namespace App\Model;

use Tracy\Debugger;


/**
 * Category management.
 */
class Category extends BaseModel {

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

	public function update($catid, $cat_val){
		$this->database->table($this->cat_table_name)->get($catid)->update($cat_val);
	}

	public function isDeletable($catid){
		return ($this->database->table($this->$raceid . parent::ENTRY_TABLE_SUFF)
			->where('category_id', $catid)->count() > 0) ? false : true;
	}

	public function delete($catid) {
		$this->load($catid)->delete();
	}
}
