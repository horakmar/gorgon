<?php

namespace App\Presenters;

use Nette;
use Nette\Application\UI\Form;
use Nextras\Forms\Rendering\Bs3FormRenderer;
use Tracy\Debugger;

class GlobalentryPresenter extends EntryPresenter
{

	public function startup(){
		parent::startup();
		$this->entry->setRace('g'); // Set global table
	}

	public function createComponentEntryForm() {
		$form = new Form;
		$form->addText('lname', 'Příjmení');
		$form->addText('fname', 'Jméno');
		$form->addText('nick', 'Nick');
		$form->addText('registration', 'Reg.');
		$form->addText('si_number', 'SI');
		$form->addSubmit('send', 'Zapsat')
			->getControlPrototype()->addClass('btn-primary');
		$form->onSuccess[] = [$this, 'entryFormSucceeded'];
		$form->setRenderer(new Bs3FormRenderer);
		return $form;
	}

	public function entryFormSucceeded($form, $values){
		$this->entry->update($this->getParameter('entryid'), $values);
		$this->redirect('Globalentry:list');
	}

	public function actionDelete($entryid){
		$this->entry->delete($entryid);
		$this->redirect('Globalentry:list');
	}


	public function renderList($entryid = NULL, $page = 1) {
		$pag = new Nette\Utils\Paginator;
		$pag->setItemCount($this->entry->listAll()->count());
		$pag->setItemsPerPage(20);
		$pag->setPage($page);
		$this->template->entries = $this->entry->listAll()->order('lname')
			->limit($pag->getLength(), $pag->getOffset());
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
		$this->redirect('Globalentry:list');
	}

}
