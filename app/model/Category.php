<?php

namespace App\Model;

use Nette;
use Tracy\Debugger;


/**
 * Users management.
 */
class Category extends Nette\Object {

	const CAT_TABLE_SUFF = '__category';
	const ENTRY_TABLE_SUFF = '__entry';

	/** @var Nette\Database\Context */
	private $database;

	/** @var string */
	private $cat_table_name, $entry_table_name;

	/** @var Nette\Database\Table\ActiveRow */
	public $category;

	public function __construct(Nette\Database\Context $database)
	{
		$this->database = $database;
	}

	public function setCategory($raceid = NULL) {
		$this->cat_table_name = $raceid . self::CAT_TABLE_SUFF;
		$this->entry_table_name = $raceid . self::ENTRY_TABLE_SUFF;
	}

	public function load($catid) {
		return $this->database->table($this->cat_table_name)->get($catid);
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
		$this->database->table($this->cat_table_name)->insert($insert_val);
	}

	public function update($catid, $cat_val){
		$this->database->table($this->cat_table_name)->get($catid)->update($cat_val);
	}

	public function isDeletable($catid){
		return ($this->database->table($this->entry_table_name)
			->where('category_id', $catid)->count() > 0) ? false : true;
	}

	public function delete($catid) {
		$this->database->table($this->cat_table_name)->get($catid)->delete();
	}
}
