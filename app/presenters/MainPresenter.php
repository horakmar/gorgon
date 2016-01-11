<?php

namespace App\Presenters;

use Nette;
use App\Model;


class MainPresenter extends BasePresenter {

	/** @var Model\Racemanager */
	private $race;


	public function __construct(Model\Race $race)
	{
		$this->race = $race;
	}


	public function renderDefault()
	{
		$this->template->races = $this->race->listAll()->order('datetime_0 DESC');
	}

}
