<?php

namespace App\Presenters;

use Nette;
use Nette\Application\UI\Form;
use Nextras\Forms\Rendering\Bs3FormRenderer;
use App\Model\RaceManager;
use Tracy\Debugger;

class EntryPresenter extends BasePresenter
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

	public function createComponentAddEntryForm() {


		$validator = function($item){
			return $this->manager->freeSinum($item->value);
		};

		$form = new Form;
		$form->addText('sinum', 'SI:')
			->setRequired('Číslo SI čipu je povinné')
			->addRule(Form::INTEGER, 'SI musí být číslo')
			->addRule($validator, 'SI je již použito.');
		$form->addText('lname', 'Příjmení:');
		$form->addText('fname', 'Jméno:');
		$form->addText('nick', 'Přezdívka:');
		$form->addSubmit('send', 'OK');
		$form->addSubmit('cancel', 'Zpět')
			->setValidationScope(false)
		    ->onClick[] = [$this, 'formCancelled'];

		$form->onSuccess[] = [$this, 'addEntryFormSucceeded'];
		$form->setRenderer(new Bs3FormRenderer);
		return $form;
	}

	public function addEntryFormSucceeded($form, $values){
		$this->manager->addEntry($values);
		$this->redirect('Race:');
	}

	public function renderDefault($raceid) {
		$this->template->raceid = $raceid;
		$this->template->race = $this->manager->getRaceInfo($raceid);
		$this->template->entries = $this->manager->listEntries($raceid);
	}

	public function formCancelled() {
		$this->redirect('Race:');
	}

}
