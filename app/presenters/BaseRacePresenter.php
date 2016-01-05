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
	protected $manager;

	public function __construct(\App\Model\RaceManager $service) {
		$this->manager = $service;
	}

	public function startup() {
		parent::startup();
		$this->manager->setRaceID($this->raceid);
	}

}
