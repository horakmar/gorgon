<?php

namespace App\Presenters;

use Nette;
use Nette\Application\UI\Form;
use Nextras\Forms\Rendering\Bs3FormRenderer;
use App\Model\RaceManager;
use Tracy\Debugger;

class RacePresenter extends BasePresenter
{
   /** @persistent */
    public $raceid;

	/** @var RaceManager */
	private $manager;

	public function injectSignFormFactory(RaceManager $service) {
		$this->manager = $service;
	}

	public function __construct()
	{
	}

	public function createComponentAddRaceForm() {


		$validator = function($item){
			return $this->manager->freeRaceid($item->value);
		};

		$form = new Form;
		$form->addText('raceid', 'ID závodu:')
			->setRequired('ID závodu je povinné')
			->addRule(Form::LENGTH, 'Délka ID musí být 4 - 8 znaků', array(4,8))
			->addRule($validator, 'ID je již použito.');
		$form->addText('name', 'Název:');
		$form->addDateTimePicker('datetime_0', 'Datum a čas:')
			->setRequired('Datum a čas jsou povinné.');
		$form->addSelect('type', 'Typ závodu',
			array('training' => 'Trénink',
			      'race'     => 'Závod'));
		$form->addTextArea('descr', 'Popis:');
		$form->addSubmit('send', 'OK');
		$form->addSubmit('cancel', 'Zpět')
			->setValidationScope(false)
		    ->onClick[] = [$this, 'formCancelled'];

		$form->onSuccess[] = [$this, 'addRaceFormSucceeded'];
		$form->setRenderer(new Bs3FormRenderer);
		return $form;
	}

	public function addRaceFormSucceeded($form, $values){
		$this->manager->addRace($values);
		$this->redirect('Main:');
	}

	public function renderDefault($raceid) {
		$this->template->raceid = $raceid;
		$this->template->race = $this->manager->getRaceInfo($raceid);
		$this->template->courses = $this->manager->listCourses($raceid);
		$this->template->categories = $this->manager->listCategories($raceid);
		$this->template->entries = $this->manager->listEntries($raceid);
	}

	public function formCancelled() {
		$this->redirect('Main:');
	}

}
