<?php

namespace App\Presenters;

use Nette;

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
			$this->template->data = $this->httpRequest->getPost();
		}
	}

}
