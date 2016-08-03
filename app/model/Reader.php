<?php

namespace App\Model;

use Tracy\Debugger;


/**
 * Result management.
 */
class Reader extends BaseModel {

	public function timeMake($stamp, $timestat, $last = 0){
		$time_b = NULL;
		$time_s = '--';
		$split_b = NULL;
		$split_s = '';

		// 12h -> 24h heuristics
		if($timestat == 1){
			if($stamp < $last){
				if($stamp < 43200){
					$stamp += 43200;
				}
			}
		}
		if($timestat > 0){
			$time_b = $stamp;
			$time_s = $this->timeFormat($time_b);
			$split_b = $stamp - $last;
			$split_s = $this->timeMinFormat($split_b);
		}
		return [$time_s, $time_b, $split_s, $split_b];
	}

	public function timeFormat($stamp){
		return strftime("%k:%M:%S", $stamp + 23*3600);
	}

	public function timeMinFormat($stamp){
		$min = (int) ($stamp / 60);
		$sec = $stamp % 60;
		$zero = ($sec < 10) ? '0' : '';
		return "$min:$zero$sec";
	}

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
