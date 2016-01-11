<?php

namespace App\Presenters;

use Nette;
use Tracy\Debugger;

/**
 * Base presenter for all application presenters.
 */
abstract class BaseRacePresenter extends BasePresenter
{
   /** @persistent */
    public $raceid = NULL;

	/** @var RaceManager */
	protected $race;

	public function __construct(\App\Model\Race $service) {
		$this->race = $service;
	}

	public function startup() {
		parent::startup();
		$this->race->setRace($this->raceid);
	}

}
