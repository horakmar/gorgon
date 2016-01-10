<?php

namespace App\Model;

use Tracy\Debugger;


/**
 * Entry management.
 */
class Entry extends BaseModel {

	public function listAll() {
		return $this->database->table($this->raceid . parent::ENTRY_TABLE_SUFF);
	}

	public function load($entryid) {
		return $this->listAll()->get($entryid);
	}

	public function update($entryid, $values) {
		if($entryid){
			$this->listAll()->get($entryid)->update($values);
		}else{
			$this->listAll()->insert($values);
		}
	}

	public function isDeletable($entryid){
		// TODO
		return true;
	}

	public function delete($entryid) {
		$this->load($entryid)->delete();
	}
}
