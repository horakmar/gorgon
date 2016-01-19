<?php

namespace App\Presenters;

use Nette;
use Nette\Application\UI\Form;
use Nextras\Forms\Rendering\Bs3FormRenderer;
use Tracy\Debugger;

class EntryPresenter extends BaseRacePresenter
{
	const GLOBTB = 1;

	public static $start_options = [
			'none' => 'Nic',
			'early' => 'Brzy',
			'late'  => 'Pozdě',
			'red'   => 'Červená',
			'orange' => 'Oranžová'
		];
	
	public static $collision_options = [
		'add' => ' Přidat',
		'replace' => ' Přepsat',
		'ignore' => ' Ignorovat'
	];

	/** @var \App\Model\Entry */
	protected $entry;
	
	public function __construct(\App\Model\Race $race, \App\Model\Entry $entry) {
		parent::__construct($race);
		$this->entry = $entry;
	}

	public function startup(){
		parent::startup();
		$this->entry->setRace($this->raceid);
	}

	public function createComponentEntryForm() {
		$form = new Form;
		$form->addText('lname', 'Příjmení');
		$form->addText('fname', 'Jméno');
		$form->addText('nick', 'Nick');
		$form->addText('registration', 'Reg.');
		$form->addText('si_number', 'SI');
		$form->addSelect('category_id', 'Kategorie', $this->race->listCategories()->fetchPairs('id','name'));
		$form->addText('start', 'Start');
		$form->addSelect('start_opt', 'Volby', self::$start_options);
		$form->addSubmit('send', 'Zapsat')
			->getControlPrototype()->addClass('btn-primary');
		$form->onSuccess[] = [$this, 'entryFormSucceeded'];
		$form->setRenderer(new Bs3FormRenderer);
		return $form;
	}

	public function createComponentEntryCopyForm() {
		$form = new Form;
		$raceentry = $form->addMultiSelect('raceentry', 'Přihlášky závodu:', $this->entry->listNames())
			->addRule(Form::FILLED, "Nic není označeno.");
		$raceentry->getControlPrototype()->size(25);
		$globalentry = $form->addMultiSelect('globalentry', 'DB závodníků :', $this->entry->listNames(self::GLOBTB))
			->addRule(Form::FILLED, "Nic není označeno.");
		$globalentry->getControlPrototype()->size(25);
		$form->addRadioList('si_collision', 'Stejné SI:', self::$collision_options)
			->setValue('add');
		$form->addSubmit('torace', '<span class="glyphicon glyphicon-arrow-left"></span>')
			->setValidationScope([$globalentry])
			->getControlPrototype()->addClass('btn-default');
		$form->addSubmit('toglobal', '<span class="glyphicon glyphicon-arrow-right"></span>')
			->setValidationScope([$raceentry])
			->getControlPrototype()->addClass('btn-default');
		$form->onSuccess[] = [$this, 'entryCopyFormSucceeded'];
		$form->setRenderer(new Bs3FormRenderer);
		return $form;
	}

	public function entryCopyFormSucceeded($form, $values){
		if($form->isSubmitted()->getName() == 'toglobal'){
			$ret = $this->entry->copy(NULL, self::GLOBTB, $values['raceentry'], $values['si_collision']);
		}else{
			$ret = $this->entry->copy(self::GLOBTB, NULL, $values['globalentry'], $values['si_collision']);
		}
		$message = '';
		foreach($ret as $mess){
			$message .= $mess . "\n";
		}
		if($message) $this->flashMessage($message);
		$this->redirect('Entry:copy');
	}

	public function entryFormSucceeded($form, $values){
		$this->entry->update($this->getParameter('entryid'), $values);
		$this->redirect('Entry:list');
	}

	public function renderCopy(){
		$this->template->raceEntries = $this->entry->listNames();
		$this->template->globalEntries = $this->entry->listNames(1);
	}

	public function renderDelete($entryid){
		$this->template->countref = $this->entry->countReferences($entryid);
		if($this->template->countref == 0){
			$this->entry->delete($entryid);
			$this->redirect('Entry:list');
		}
	}

	public function renderList($entryid = NULL) {
		$this->template->raceid = $this->raceid;
		$this->template->entries = $this->entry->listAll()->order('lname');
		$this->template->addFilter('startopt', function($s){
        	return self::$start_options[$s];
		});
		if($entryid){
			$form = $this['entryForm'];
			$entry = $this->entry->load($entryid);
			if (!$entry) {
				$this->error('Záznam nenalezen!');
			}
			$form->setDefaults($entry);
		}
	}

	public function formCancelled() {
		$this->redirect('Entry:list');
	}

}
