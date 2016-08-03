<?php

namespace App\Presenters;

use Nette;
use Nette\Application\UI\Form;
use Nextras\Forms\Rendering\Bs3FormRenderer;
use App\Model\Entry;
use Grido\Grid;
use Tracy\Debugger;

class EntryPresenter extends BaseRacePresenter {

	public static $start_options = [
		Entry::STRT_NONE => 'Nic',
		Entry::STRT_EARLY => 'Brzy',
		Entry::STRT_LATE  => 'Pozdě',
		Entry::STRT_RED   => 'Červená',
		Entry::STRT_ORANGE => 'Oranžová'
	];
	
	public static $collision_options = [
		Entry::COLL_ADD => ' Přidat',
		Entry::COLL_RPL => ' Přepsat',
		Entry::COLL_IGN => ' Ignorovat'
	];

	/** @var Entry */
	protected $entry;
	
	public function __construct(\App\Model\Race $race, Entry $entry) {
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
		$globalentry = $form->addMultiSelect('globalentry', 'DB závodníků :', $this->entry->listNames(Entry::GLOBTB))
			->addRule(Form::FILLED, "Nic není označeno.");
		$globalentry->getControlPrototype()->size(25);
		$form->addRadioList('si_collision', 'Stejné SI:', self::$collision_options)
			->setValue(Entry::COLL_ADD);
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
			$ret = $this->entry->copy(NULL, Entry::GLOBTB, $values['raceentry'], $values['si_collision']);
		}else{
			$ret = $this->entry->copy(Entry::GLOBTB, NULL, $values['globalentry'], $values['si_collision']);
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

	public function renderList($entryid = NULL, $page = 1) {
		$pag = new Nette\Utils\Paginator;
		$pag->setItemCount($this->entry->listAll()->count());
		$pag->setItemsPerPage(10);
		$pag->setPage($page);
		
		$this->template->pag = $pag;
		$this->template->raceid = $this->raceid;
		$this->template->entries = $this->entry->listAll()->order('lname')
			->limit($pag->getLength(), $pag->getOffset());
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

	protected function createComponentGrid($name)
	{
		$grid = new Grid($this, $name);
		$grid->setModel($this->entry->listAll());

		$grid->addColumnText('lname', 'Příjmení')
			->setSortable()
			->setFilterText()
				->setSuggestion();
		$grid->addColumnText('fname', 'Jméno')
			->setSortable()
			->setFilterText()
				->setSuggestion();
		$grid->addColumnText('nick', 'Nick')
			->setFilterText();
		$grid->addColumnText('registration', 'Reg.')
			->setSortable()
			->setFilterText();
		$grid->addColumnText('si_number', 'SI')
			->setSortable()
			->setFilterText();
		$grid->addColumnText('category', 'Kat.')
			->setColumn(function($i){
				return $i->category['name'];
		   	});
		$grid->addColumnNumber('start', 'Start')
			->setSortable();
		$grid->addColumnText('start_opt', 'Volby')
			->setColumn(function($i){
				return self::$start_options[$i->start_opt];
		   	});
		$cats = $this->race->listCategories()->fetchPairs('id','name');
		$catselect = array('' => '');
		foreach($cats as $k => $v){
			$catselect[$k] = $v;
		}

		$grid->addFilterSelect('category', 'Kat.', $catselect)
			->setColumn('category_id');

		$grid->filterRenderType = \Grido\Components\Filters\Filter::RENDER_INNER;
		$grid->setExport();
	}

	public function renderListg() {
		$this->template->raceid = $this->raceid;
		$this->template->ajax = 1;
	}

}
