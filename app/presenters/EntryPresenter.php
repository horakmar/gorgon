<?php

namespace App\Presenters;

use Nette;
use Nette\Application\UI\Form;
use Nextras\Forms\Rendering\Bs3FormRenderer;
use Tracy\Debugger;

class EntryPresenter extends BaseRacePresenter
{
	public static $start_options = [
			'none' => 'Nic',
			'early' => 'Brzy',
			'late'  => 'Pozdě',
			'red'   => 'Červená',
			'orange' => 'Oranžová'
		];

	/** @var \App\Model\Entry */
	private $entry;
	
	public function __construct(\App\Model\RaceManager $raceman, \App\Model\Entry $entry) {
		parent::__construct($raceman);
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
		$form->addSelect('category_id', 'Kategorie', $this->manager->listCategories()->fetchPairs('id','name'));
		$form->addText('start', 'Start');
		$form->addSelect('start_opt', 'Volby', self::$start_options);
		$form->addSubmit('send', 'Zapsat')
			->getControlPrototype()->addClass('btn-primary');
		$form->onSuccess[] = [$this, 'entryFormSucceeded'];
		$form->setRenderer(new Bs3FormRenderer);
		return $form;
	}

	public function entryFormSucceeded($form, $values){
		$this->entry->update($this->getParameter('entryid'), $values);
		$this->redirect('Entry:list');
	}

	public function actionDelete($entryid){
		if($this->entry->isDeletable($entryid)){
			$this->entry->delete($entryid);
			$this->redirect('Entry:list');
		}
	}

	public function renderDelete($catid){
	}

	public function renderList($entryid = NULL) {
		$this->template->raceid = $this->raceid;
		$this->template->entries = $this->entry->listAll();
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
