<?php

namespace App\Presenters;

use Nette;
use Nette\Utils\Json;

class ReaderPresenter extends BaseRacePresenter
{

	/** @var App\Model\Reader */
	private $reader;

	public function __construct(\App\Model\Race $race, \App\Model\Reader $reader) {
		parent::__construct($race);
		$this->reader = $reader;
	}

	public function startup(){
		parent::startup();
		$this->reader->setRace($this->raceid);
	}

	public function renderReader(){
		$this->getHttpResponse()->setContentType('text/plain', 'UTF-8');
		$this->template->method = $this->getHttpRequest()->getMethod();
		if($this->template->method == 'POST'){
#			\Tracy\Debugger::$productionMode = TRUE;
			$jsondata = $this->getHttpRequest()->getRawBody();
			$jsdata = Json::decode($jsondata);
			$data['si_number'] = $jsdata->si_number;
			$data['si_type'] = $jsdata->si_type;
			$data['si_lname'] = $jsdata->si_lname;
			$data['si_fname'] = $jsdata->si_fname;
			$data['tm_clear'] = $jsdata->tm_clear;
			$data['tm_check'] = $jsdata->tm_check;
			$data['tm_start'] = $jsdata->tm_start;
			$data['tm_finish'] = $jsdata->tm_finish;
			$punches = [];
			foreach($jsdata->punches as $punch){
				$cn = $punch->cn;
				$time = $punch->time;
				$punches[] = ['cpcode' => $cn, 'cptime' => $time];
			}	
			$this->reader->insertRead($data, $punches);
			$this->template->data = $data;
			$this->template->punches = $punches;
		}else{
			$this->template->raceid = $this->raceid;
		}
	}
}
