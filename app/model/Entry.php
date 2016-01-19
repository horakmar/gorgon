<?php

namespace App\Model;

use Tracy\Debugger;


/**
 * Entry management.
 */
class Entry extends BaseModel {

	const GLOBTB = 1;

	public function listAll($global = NULL) {
		$table = ($global) ? 'g' : $this->raceid;
		return $this->database->table($table . parent::ENTRY_TABLE_SUFF);
	}

	public function listNames($global = NULL) {
		return $this->listAll($global)->select('id, CONCAT_WS(" ",lname,nick,fname, "SI:",si_number) AS name')
			->order('name')->fetchPairs('id', 'name');
	}

	public function load($entryid) {
		return $this->listAll()->get($entryid);
	}

	public function getBySI($sinum, $global = NULL){
		return $this->listAll($global)->where('si_number', $sinum);
	}

	public function update($entryid, $values) {
		if($entryid){
			$this->listAll()->get($entryid)->update($values);
		}else{
			$this->listAll()->insert($values);
		}
	}

	public function countReferences($entryid){
		// TODO
		return 0;
	}

	public function delete($entryid) {
		$this->load($entryid)->delete();
	}

	public function copy($from, $to, $entries, $si_collision){
		$select = $this->listAll($from)->select('si_number, lname, fname, nick, registration, birth')->where('id', $entries);
		$copy = []; $ret = [];
		foreach($select as $row){
			$same_si = $this->getBySI($row['si_number'], $to);
			if($same_si->count() > 0){
				switch($si_collision){
				case 'ignore':
					$ret[] = "Ignorováno: {$row['lname']} {$row['nick']} {$row['fname']}, SI: {$row['si_number']}";
					continue 2;
				case 'replace':
					foreach($same_si as $rep){
						$ret[] = "Přepsáno: {$rep['lname']} {$rep['nick']} {$rep['fname']}, SI: {$rep['si_number']}";
					}
					$same_si->delete();
					break;
				}
			}
			$copy[] = $row;
		}
		if(count($copy) > 0){
			$this->listAll($to)->insert($copy);
		}
		return $ret;
	}

}
