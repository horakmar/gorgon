<?php

namespace App\Model;

use Nette;

/**
 * Parent model class.
 */
abstract class BaseModel extends Nette\Object {

	const CATEGORY_TABLE_SUFF = '__category';
	const ENTRY_TABLE_SUFF = '__entry';
	const COURSE_TABLE_SUFF = '__course';
	const CP_TABLE_SUFF = '__course_cp';
	const READ_TABLE_SUFF = '__read';
	const READPUNCH_TABLE_SUFF = '__read_punch';


	/** @var Nette\Database\Context */
	protected $database;

	/** @var string */
	protected $raceid;

	public function __construct(Nette\Database\Context $database)
	{
		$this->database = $database;
	}

	public function setRace($raceid = NULL) {
		$this->raceid = $raceid;
	}

	public static function empty2Null($data) {
		return ($data == '') ? NULL : $data;
	}

	public static function time2sec($datetime) {
		list($h, $m, $s) = explode(":", explode(" ", $datetime)[1]);
		return $h*3600 + $m*60 + $s;
	}
}
