<?php

namespace App\Presenters;

use Nette;
use Nette\Utils\Json;
use Nette\Application\Responses\JsonResponse;
use Latte;

class ReaderPresenter extends BaseRacePresenter
{

	/** @var App\Model\Reader */
	private $reader;

	/** @var Nette\Bridges\ApplicationLatte\ILatteFactory @inject */
    public $latteFactory;

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
			$raceinfo = $this->race->getRaceInfo();
			$jsondata = $this->getHttpRequest()->getRawBody();
			$jsdata = Json::decode($jsondata);
			if(isset($jsdata->init)){
				if($raceinfo){
					$this->sendResponse(new JsonResponse(['status' => 'OK']));
				}else{
					$this->sendResponse(new JsonResponse(['status' => 'Err', 'error' => 'NoRace']));
				}
			}else{

				$data['si_number'] = $jsdata->si_number;
				$data['si_type'] = $jsdata->si_type;
				$data['si_lname'] = $jsdata->si_lname;
				$data['si_fname'] = $jsdata->si_fname;
				$data['tm_clear'] = ($jsdata->clear->tst == 0) ? NULL : $jsdata->clear->time;
				$data['tm_check'] = ($jsdata->check->tst == 0) ? NULL : $jsdata->check->time;
				$data['tm_start'] = ($jsdata->start->tst == 0) ? NULL : $jsdata->start->time;
				$data['tm_finish'] = ($jsdata->finish->tst == 0) ? NULL : $jsdata->finish->time;
				$punches = [];
				foreach($jsdata->punches as $punch){
					$punches[] = ['cpcode' => $punch->cn, 'cptime' => ($punch->tst == 0) ? NULL : $punch->time];
				}	
				$this->reader->insertRead($data, $punches);

				// Data for displaying or printing
				$data['si_name'] = $data['si_fname'] . ' ' . $data['si_lname'];
				if($data['si_name'] == ' ') $data['si_name'] = '';
				list($data['clear'],,,) = $this->reader->timeMake($jsdata->clear->time, $jsdata->clear->tst, $raceinfo['time_0']);
				list($data['check'],,,) = $this->reader->timeMake($jsdata->check->time, $jsdata->check->tst, $raceinfo['time_0']);
				list($data['start'], $lastpunch,,) = $this->reader->timeMake($jsdata->start->time, $jsdata->start->tst, $raceinfo['time_0']);
				list($data['finish'],,,) = $this->reader->timeMake($jsdata->finish->time, $jsdata->finish->tst, $raceinfo['time_0']);

				if($lastpunch == 0) {
					$lastpunch = $raceinfo['time_0'];
				}
				$i = 0;
				foreach($jsdata->punches as $punch){
					list($punches[$i]['time_s'], $lastpunch, $punches[$i]['split_s'],) = $this->reader->timeMake($punch->time, $punch->tst, $lastpunch);
					$i++;
				}	

				$params = ['data' => $data, 'punches' => $punches, 'time_0' => $this->reader->timeFormat($raceinfo['time_0'])];

				$latte = $this->latteFactory->create();
				$printer_out = $latte->renderToString('../app/templates/Reader/print.latte', $params);
				$display_out = $latte->renderToString('../app/templates/Reader/display.latte', $params);
				$this->sendResponse(new JsonResponse(['printer_out' => $printer_out, 'display_out' => $display_out]));
			}
		}else{
			$this->template->raceid = $this->raceid;
		}
	}
}
