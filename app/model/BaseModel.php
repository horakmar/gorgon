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
}
