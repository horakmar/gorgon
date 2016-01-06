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
		$this->category->setCategory($this->raceid);
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

	public function addCategoryFormSucceeded($form, $values){
		$cat_val['name'] = $form->getHttpData($form::DATA_LINE, 'name[]');
		$cat_val['course_id'] = $form->getHttpData($form::DATA_LINE, 'course_id[]');

		$this->category->save($this->getParameter('catid'), $cat_val);
		$this->redirect('Category:list');
	}

	public function renderAdd($numcat){
		$this->template->numcat = $numcat;
		$this->template->courses = $this->manager->listCourses()->fetchPairs('id','name');
	}

	public function renderEdit($catid){
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
