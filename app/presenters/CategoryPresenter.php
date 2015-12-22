<?php

namespace App\Presenters;

use Nette;
use Nette\Application\UI\Form;
use Nextras\Forms\Rendering\Bs3FormRenderer;
use App\Model\RaceManager;
use Tracy\Debugger;

class CategoryPresenter extends BasePresenter
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

	public function createComponentAddCategoryForm() {
		$form = new Form;
		$form->addText('name', 'Název:')
			->setRequired('Název je povinný');
		$form->addSelect('course', 'Trať',
			$manager->listCourses($raceid));
		$form->addSubmit('send', 'OK');
		$form->addSubmit('cancel', 'Zpět')
			->setValidationScope(false)
		    ->onClick[] = [$this, 'formCancelled'];

		$form->onSuccess[] = [$this, 'addCategoryFormSucceeded'];
		$form->setRenderer(new Bs3FormRenderer);
		return $form;
	}

	public function addCategoryFormSucceeded($form, $values){
		$this->manager->addCategory($values);
		$this->redirect('Race:');
	}

	public function renderDefault($raceid) {
		$this->template->raceid = $raceid;
		$this->template->race = $this->manager->getRaceInfo($raceid);
		$this->template->categories = $this->manager->listCategories($raceid);
	}

	public function formCancelled() {
		$this->redirect('Race:');
	}

}
