<?php

namespace App\Presenters;

use Nette;
use Nette\Application\UI\Form;
use Nextras\Forms\Rendering\Bs3FormRenderer;
use App\Model\RaceManager;
use Tracy\Debugger;

class CoursePresenter extends BasePresenter
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

	public function createComponentCourseForm() {
		$form = new Form;
		$form->addText('name', 'Název:')
			->setRequired('Název je povinný');
		$form->addSubmit('send', 'OK');
		$form->addSubmit('cancel', 'Zpět')
			->setValidationScope(false)
		    ->onClick[] = [$this, 'formCancelled'];

		$form->onSuccess[] = [$this, 'courseFormSucceeded'];
		$form->setRenderer(new Bs3FormRenderer);
		return $form;
	}

	public function courseFormSucceeded($form, $values){
		$val2 = $form->getValues();
		Debugger::bardump($val2);
#		$this->manager->addCourse($values);
		$this->redirect("Race:", $this->raceid);
	}

	public function renderAdd($raceid){
		$this->template->numcontrols = 6;
	}

	public function renderDefault($raceid) {
		$this->template->raceid = $raceid;
		$this->template->race = $this->manager->getRaceInfo($raceid);
		$this->template->courses = $this->manager->listCourses($raceid);
	}

	public function formCancelled() {
		$this->redirect("Race:", $this->raceid);
	}

}
