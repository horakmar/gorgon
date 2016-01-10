<?php

namespace App\Presenters;

use Nette;
use Nette\Application\UI\Form;
use Nextras\Forms\Rendering\Bs3FormRenderer;
use Tracy\Debugger;

class CategoryPresenter extends BaseRacePresenter
{

	/** @var \App\Model\Category */
	private $category;
	
	public function __construct(\App\Model\RaceManager $raceman, \App\Model\Category $category) {
		parent::__construct($raceman);
		$this->category = $category;
	}

	public function startup(){
		parent::startup();
		$this->category->setRace($this->raceid);
	}

	public function createComponentAddCategoryForm() {
		$form = new Form;
		$form->addSubmit('send', 'OK')
			->getControlPrototype()->addClass('btn-primary');
		$cancel = $form->addSubmit('cancel', 'Zpět')
			->setValidationScope(false);
		$cancel->onClick[] = [$this, 'formCancelled'];
		$cancel->getControlPrototype()->addClass('btn-default');
		
		$form->onSuccess[] = [$this, 'addCategoryFormSucceeded'];
		$form->setRenderer(new Bs3FormRenderer);
		return $form;
	}

	public function createComponentEditCategoryForm() {
		$form = new Form;
		$form->addText('name', 'Kategorie')
			->setRequired('Název kategorie je povinný');
		$form->addSelect('course_id', 'Trať', $this->course->listAll()->fetchPairs('id','name'));
		$form->addSubmit('send', 'OK')
			->getControlPrototype()->addClass('btn-primary');
		$cancel = $form->addSubmit('cancel', 'Zpět')
			->setValidationScope(false);
		$cancel->onClick[] = [$this, 'formCancelled'];
		$cancel->getControlPrototype()->addClass('btn-default');
		
		$form->onSuccess[] = [$this, 'editCategoryFormSucceeded'];
		$form->setRenderer(new Bs3FormRenderer);
		return $form;
	}

	public function editCategoryFormSucceeded($form, $values){
		$this->category->update($this->getParameter('catid'), $values);
		$this->redirect('Category:list');
	}

	public function addCategoryFormSucceeded($form, $values){
		$cat_val['name'] = $form->getHttpData($form::DATA_LINE, 'name[]');
		$cat_val['course_id'] = $form->getHttpData($form::DATA_LINE, 'course_id[]');

		$this->category->insert($cat_val);
		$this->redirect('Category:list');
	}

	public function renderAdd($numcat){
		$this->template->numcat = $numcat;
		$this->template->courses = $this->manager->listCourses()->fetchPairs('id','name');
	}

	public function renderEdit($catid = 0){
		$form = $this['editCategoryForm'];
		$cat = $this->category->load($catid);
		if (!$cat) {
			$this->error('Záznam nenalezen!');
		}
		Debugger::bardump($cat);
		$form->setDefaults($cat);
	}

	public function actionDelete($catid){
		if($this->category->isDeletable($catid)){
			$this->category->delete($catid);
			$this->redirect('Category:list');
		}
	}

	public function renderDelete($catid){
	}

	public function renderList() {
		$this->template->raceid = $this->raceid;
		$this->template->race = $this->manager->getRaceInfo();
		$this->template->categories = $this->manager->listCategories();
	}

	public function formCancelled() {
		$this->redirect('Category:list');
	}

}
