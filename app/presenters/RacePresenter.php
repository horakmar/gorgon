<?php

namespace App\Presenters;

use Nette;
use Nette\Application\UI\Form;
use Nextras\Forms\Rendering\Bs3FormRenderer;
use Tracy\Debugger;

class RacePresenter extends BaseRacePresenter
{

	public function createComponentAddRaceForm() {

		$validator = function($item){
			return $this->manager->freeRaceid($item->value);
		};

		$race_type = [
			'training' => 'Trénink',
			'race'     => 'Závod'
		];

		$form = new Form;
		$form->addText('raceid', 'ID závodu:')
			->setRequired('ID závodu je povinné')
			->addRule(Form::LENGTH, 'Délka ID musí být 4 - 8 znaků', array(4,8))
			->addRule($validator, 'ID je již použito.');
		$form->addText('name', 'Název:');
		$form->addDateTimePicker('datetime_0', 'Datum a čas:')
			->setRequired('Datum a čas jsou povinné.');
		$form->addSelect('type', 'Typ závodu', $race_type);
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
		$this->manager->addRace($values);
		$this->redirect('Main:');
	}

	public function raceDeleteSucceeded($form, $val){
		$this->manager->delRace($this->getParameter('raceid'));
		$this->redirect("Main:");
	}
	
	public function renderDefault($raceid) {
		$this->raceid = $raceid;
		$this->template->raceid = $this->raceid;
		$this->template->race = $this->manager->getRaceInfo();
		$this->template->courses = $this->manager->listCourses();
		$this->template->categories = $this->manager->listCategories();
		$this->template->entries = $this->manager->listEntries();
	}

	public function renderDelete($raceid) {
		$this->raceid = $raceid;
		$this->template->race = $this->manager->getRaceInfo();
	}


	public function formCancelled() {
		$this->redirect('Main:');
	}

}
