<?php

namespace App\Model;

use Tracy\Debugger;


/**
 * Result management.
 */
class Reader extends BaseModel {

	public function listAll() {
		return $this->database->table($this->raceid . parent::READ_TABLE_SUFF);
	}

	public function load($readid) {
		return $this->listAll()->get($readid);
	}

	public function insertRead($data, $punches) {
		$readid = $this->listAll()->insert($data)->getPrimary();
		foreach($punches as &$punch){
			$punch['read_id'] = $readid;
		}
		if(count($punches) > 0){
			$this->database->table($this->raceid . parent::READPUNCH_TABLE_SUFF)->insert($punches);
		}
	}

	public function getBySI($sinum){
		return $this->listAll()->where('si_number', $sinum);
	}

	public function delete($readid) {
		$this->load($readid)->delete();
	}

}
