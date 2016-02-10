<?php

namespace App\Presenters;

use Nette;
use Nette\Application\UI\Form;
use Nextras\Forms\Rendering\Bs3FormRenderer;
use App\Model\Category;
use Tracy\Debugger;

class CategoryPresenter extends BaseRacePresenter {

	public static $start_order = [
		Category::STORD_LIST_SI => 'Startovka, čip',
		Category::STORD_SI_LIST => 'Čip, startovka',
		Category::STORD_LIST => 'Jen startovka',
		Category::STORD_SI => 'Jen čip'
	];

	/** @var Category */
	private $category;
	
	public function __construct(\App\Model\Race $race, Category $category) {
		parent::__construct($race);
		$this->category = $category;
	}

	public function startup(){
		parent::startup();
		$this->category->setRace($this->raceid);
	}

	public function createComponentCategoryForm() {
		$form = new Form;
		$form->addText('name', 'Kategorie')
			->setRequired('Název kategorie je povinný');
		$form->addSelect('course_id', 'Trať', $this->race->listCourses()->fetchPairs('id','name'));
		$form->addSelect('start_order', 'Start', self::$start_order);
		$form->addSubmit('send', 'OK')
			->getControlPrototype()->addClass('btn-primary');
		$form->onSuccess[] = [$this, 'categoryFormSucceeded'];
		$form->setRenderer(new Bs3FormRenderer);
		return $form;
	}

	public function createComponentCategoryDeleteConfirm() {
		$form = new Form;
		$form->addSubmit('cancel', 'Zpět')
			->onClick[] = [$this, 'formCancelled'];
		$form->addSubmit('send', 'OK');
		$form->onSuccess[] = [$this, 'categoryDeleteSucceeded'];
		$form->setRenderer(new Bs3FormRenderer);
		return $form;
	}

	public function categoryDeleteSucceeded($form, $val){
		$this->category->delete($this->getParameter('catid'));
		$this->redirect('Category:list');
	}

	public function categoryFormSucceeded($form, $values){
		$this->category->update($this->getParameter('catid'), $values);
		$this->redirect('Category:list');
	}

	public function renderDelete($catid){
		$this->template->refcount = $this->category->countReferences($catid);
		if($this->template->refcount == 0){
			$this->category->delete($catid);
			$this->redirect('Category:list');
		}else{
			$this->template->category = $this->category->load($catid);
		}
	}

	public function renderList($catid = NULL) {
		$this->template->raceid = $this->raceid;
		$this->template->race = $this->race->getRaceInfo();
		$this->template->categories = $this->category->listAll()->order('name');
		$this->template->addFilter('startorder', function($s){
        	return self::$start_order[$s];
		});

		if($catid){
			$form = $this['categoryForm'];
			$category = $this->category->load($catid);
			if (!$category) {
				$this->error('Záznam nenalezen!');
			}
			$form->setDefaults($category);
		}

	}

	public function formCancelled() {
		$this->redirect('Category:list');
	}

}
