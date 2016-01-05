<?php

namespace App\Presenters;

use Nette;
use Nette\Application\UI\Form;
use Nextras\Forms\Rendering\Bs3FormRenderer;
use Tracy\Debugger;

class CategoryPresenter extends BaseRacePresenter
{

	/** @var array */
//	private $courses;

	public function createComponentCategoryForm() {
		$form = new Form;
		for($i = 0; $i <= $this->template->numcat; $i++) {
			$form->addText('name' . $i, 'Název');
			$form->addSelect('course' . $i, 'Trať',
				$this->manager->listCourses()->fetchPairs('id','name'));
		}
		$form->addSubmit('send', 'OK')
			->getControlPrototype()->addClass('btn-primary');
		$cancel = $form->addSubmit('cancel', 'Zpět')
			->setValidationScope(false);
		$cancel->onClick[] = [$this, 'formCancelled'];
		$cancel->getControlPrototype()->addClass('btn-default');
		
		$form->onSuccess[] = [$this, 'categoryFormSucceeded'];
		$form->setRenderer(new Bs3FormRenderer);
		return $form;
	}

	public function categoryFormSucceeded($form, $values){
		$this->manager->addCategory($values);
		$this->redirect('Race:');
	}

	public function renderAdd(){
		$this->template->setFile(__DIR__. "/templates/Category/categoryform.latte");
		$this->template->numcat = 5;
		$this->template->title = "Kategorie";
	}

	public function renderDefault() {
		$this->template->raceid = $this->raceid;
		$this->template->race = $this->manager->getRaceInfo();
		$this->template->categories = $this->manager->listCategories();
	}

	public function formCancelled() {
		$this->redirect('Race:');
	}

}
