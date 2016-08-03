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

	public static $race_kind = [
		Race::KIND_REGULAR => 'Normální',
		Race::KIND_FREEO   => 'Freeorder'
	];

	public static $inco_list = [
		Race::INCO_LAST => 'Na konec',
		Race::INCO_DISK => 'Disk',
		Race::INCO_PENA => 'Penalizace'
	];
		

	public function createComponentRaceForm() {

		$validator = function($item){
			return $this->race->freeRaceid($item->value);
		};

		$form = new Form;
		$i = $form->addText('raceid', 'ID závodu:');
		if($this->getAction() == 'add'){
			$i->setRequired('ID závodu je povinné')
			->addRule(Form::LENGTH, 'Délka ID musí být 4 - 8 znaků', array(4,8))
			->addRule($validator, 'ID je již použito.');
		}else{
			$i->setDisabled(TRUE);
		}
		$form->addText('name', 'Název:');
		$form->addDateTimePicker('datetime_0', 'Datum a čas:')
			->setRequired('Datum a čas jsou povinné.');
		$form->addSelect('type', 'Typ závodu', self::$race_type);
		$form->addSelect('opt_preftype', 'Druh závodu', self::$race_kind);
		$form->addSelect('opt_incomplete', 'Při chybějící kontrole', self::$inco_list);
		$form->addCheckbox('opt_autocat', 'Automatická kategorie');
		$form->addCheckbox('opt_namefrsi', 'Doplnit jméno z SI čipu');
		$form->addCheckbox('opt_addnew', 'Hodnotit i nepřihlášené');
		
		$form->addTextArea('descr', 'Popis:');
		$form->addSubmit('send', 'OK');
		$form->addSubmit('cancel', 'Zpět')
			->setValidationScope(false)
		    ->onClick[] = [$this, 'formCancelled'];
		
		if($this->getAction() == 'add'){
			$form->onSuccess[] = [$this, 'addRaceFormSucceeded'];
		}else{
			$form->onSuccess[] = [$this, 'editRaceFormSucceeded'];
		}
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

	public function addRaceFormSucceeded($form, $values) {
		$this->race->addRace($values);
		$this->redirect('Main:');
	}

	public function editRaceFormSucceeded($form, $values) {
		$this->race->editRace($values);
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
		Debugger::bardump($this->template->race);
		$this->template->courses = $this->race->listCourses()->order('name')->limit(10);
		$this->template->categories = $this->race->listCategories()->order('name')->limit(10);
		$p = $this->race->listEntries();
		$this->template->entry_count = count($p);
		$this->template->entries = $p->order('lname')->limit(10);
	}

	public function renderEdit($raceid) {
		$race = $this->race->load($raceid);
		$form = $this['raceForm'];
		$form->setDefaults($race);
	}

	public function renderDelete($raceid) {
		$this->raceid = $raceid;
		$this->template->race = $this->race->getRaceInfo();
	}

	public function formCancelled() {
		$this->redirect('Main:');
	}

}
