<?php

namespace App\Presenters;

use Nette;
use Nette\Utils\Json;

class ReaderPresenter extends BaseRacePresenter
{

/*
	public function __construct(\App\Model\Race $race, \App\Model\Category $category) {
		parent::__construct($race);
		$this->category = $category;
	}
 */
	/**	@inject @var Nette\Http\Request */
	public $httpRequest;

	public function renderReader(){
		$this->template->method = $this->httpRequest->getMethod();
		if($this->template->method == 'POST'){
			$jsondata = $this->httpRequest->getRawBody();
			$data = Json::decode($jsondata);
			$this->template->si_number = $data->si_number;
			$this->template->tm_clear = $data->tm_clear;
			$this->template->tm_check = $data->tm_check;
			$this->template->tm_start = $data->tm_start;
			$this->template->tm_finish = $data->tm_finish;
			$this->template->punches = [];
			foreach($data->punches as $punch){
				$cn = $punch->cn;
				$time = $punch->time;
				$this->template->punches[] = ['cn' => $cn, 'time' => $time];
			}	
		}
	}
}
