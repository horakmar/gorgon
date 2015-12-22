<?php

namespace App\Presenters;

use Nette;
use App\Model;


class MainPresenter extends BasePresenter
{

	/** @var Model\Racemanager */
	private $races;


	public function __construct(Model\RaceManager $races)
	{
		$this->races = $races;
	}


	public function renderDefault()
	{
		$this->template->races = $this->races->listRaces()->order('datetime_0 DESC');
	}

}
