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
		$form->addText('name', 'Název tratě:')
			->setRequired('Název je povinný');
		$form->addText('length', 'Délka tratě:');
		$form->addText('climb', 'Stoupání:');
		$form->addSubmit('send', 'OK')
			->setAttribute('onclick', 'unDisable()');
		$form->addSubmit('cancel', 'Zpět')
			->setValidationScope(false)
		    ->onClick[] = [$this, 'formCancelled'];

		$form->onSuccess[] = [$this, 'courseFormSucceeded'];
		$form->setRenderer(new Bs3FormRenderer);
		return $form;
	}

	public function courseFormSucceeded($form, $course_val){
		$cp_val['cpcode'] = $form->getHttpData($form::DATA_LINE, 'cpcode[]');
		$cp_val['cptype'] = $form->getHttpData($form::DATA_LINE, 'cptype[]');
		$cp_val['cpsect'] = $form->getHttpData($form::DATA_LINE, 'cpsect[]');
		$cp_val['cpchange'] = $form->getHttpData($form::DATA_LINE, 'cpchange[]');
		$cp_val['cpdata'] = $form->getHttpData($form::DATA_LINE, 'cpdata[]');
		$this->manager->putCourse($this->raceid, $course_val, $cp_val);
		$this->redirect("Race:", $this->raceid);
	}

	public function renderAdd($raceid){
		$this->template->numcontrols = 6;
		$this->template->numbonuses = 3;
	}

	public function renderEdit($raceid, $courseid){
		// TODO
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
