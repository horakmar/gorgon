<?php

namespace App\Model;

use Nette;
use Tracy\Debugger;


/**
 * Users management.
 */
class Category extends Nette\Object {

	const CAT_TABLE_SUFF = '__category';

	/** @var Nette\Database\Context */
	private $database;

	/** @var Nette\Database\Table\Selection */
	private $cat_table;

	/** @var string */
	private $cat_table_name;

	/** @var Nette\Database\Table\ActiveRow */
	public $category;

	public function __construct(Nette\Database\Context $database)
	{
		$this->database = $database;
	}

	public function setCategory($raceid = NULL) {
		$this->cat_table_name = $raceid . self::CAT_TABLE_SUFF;
		$this->cat_table = $this->database->table($this->cat_table_name);
	}

	public function load($catid) {
		$this->category = $this->cat_table->get($catid);
		return $this;
	}

	public function save($catid, $cat_val){
		if($catid){
			$this->cat_table->get($catid)->update($cat_val);
		}else{
			// Prasarna, predelat!!!
			foreach($cat_val['name'] as $key => $val){
				if($val != ''){
					$this->cat_table->insert(['name' => $val, 'course_id' => $cat_val['course_id'][$key]]);
				}
			}
		}
	}

	public function delete($catid) {
		$this->crs_table->get($catid)->delete();
	}
}
