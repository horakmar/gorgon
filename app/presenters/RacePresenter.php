<?php

namespace App\Presenters;

use Nette;
use Nette\Application\UI\Form;
use Nextras\Forms\Rendering\Bs3FormRenderer;
use Tracy\Debugger;
use App\Model\Race;

class RacePresenter extends BaseRacePresenter
{
	public static $race_type = [
		Race::TYPE_TRAINING => 'Trénink',
		Race::TYPE_RACE     => 'Závod'
	];

	public function createComponentAddRaceForm() {

		$validator = function($item){
			return $this->race->freeRaceid($item->value);
		};

		$form = new Form;
		$form->addText('raceid', 'ID závodu:')
			->setRequired('ID závodu je povinné')
			->addRule(Form::LENGTH, 'Délka ID musí být 4 - 8 znaků', array(4,8))
			->addRule($validator, 'ID je již použito.');
		$form->addText('name', 'Název:');
		$form->addDateTimePicker('datetime_0', 'Datum a čas:')
			->setRequired('Datum a čas jsou povinné.');
		$form->addSelect('type', 'Typ závodu', self::$race_type);
		$form->addTextArea('descr', 'Popis:');
		$form->addSubmit('send', 'OK');
		$form->addSubmit('cancel', 'Zpět')
			->setValidationScope(false)
		    ->onClick[] = [$this, 'formCancelled'];

		$form->onSuccess[] = [$this, 'addRaceFormSucceeded'];
		$form->setRenderer(new Bs3FormRenderer);
		return $form;
	}

	public function createComponentRaceDeleteConfirm() {
		$form = new Form;
		$form->addSubmit('cancel', 'Zpět')
			->onClick[] = [$this, 'formCancelled'];
		$form->addSubmit('send', 'OK');
		$form->onSuccess[] = [$this, 'raceDeleteSucceeded'];
		$form->setRenderer(new Bs3FormRenderer);
		return $form;
	}

	public function addRaceFormSucceeded($form, $values){
		$this->race->addRace($values);
		$this->redirect('Main:');
	}

	public function raceDeleteSucceeded($form, $val){
		$this->race->delRace($this->getParameter('raceid'));
		$this->redirect("Main:");
	}
	
	public function renderDefault($raceid) {
		$this->raceid = $raceid;
		$this->template->raceid = $this->raceid;
		$this->template->race = $this->race->getRaceInfo();
		$this->template->courses = $this->race->listCourses()->order('name')->limit(10);
		$this->template->categories = $this->race->listCategories()->order('name')->limit(10);
		$p = $this->race->listEntries();
		$this->template->entry_count = count($p);
		$this->template->entries = $p->order('lname')->limit(10);
	}

	public function renderDelete($raceid) {
		$this->raceid = $raceid;
		$this->template->race = $this->race->getRaceInfo();
	}


	public function formCancelled() {
		$this->redirect('Main:');
	}

}
