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

	/** @persistent */
	public $courseid;

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
			->setAttribute('onclick', 'unDisable()')
			->getControlPrototype()->addClass('btn-primary');
		$cancel = $form->addSubmit('cancel', 'Zpět')
			->setValidationScope(false);
		$cancel->getControlPrototype()->addClass('btn-default');
		$cancel->onClick[] = [$this, 'formCancelled'];

		$form->onSuccess[] = [$this, 'courseFormSucceeded'];
		$form->setRenderer(new Bs3FormRenderer);
		return $form;
	}

	public function createComponentCourseDeleteConfirm() {
		$form = new Form;
		$form->addSubmit('cancel', 'Zpět')
			->onClick[] = [$this, 'formCancelled'];
		$form->addSubmit('send', 'OK');
		$form->onSuccess[] = [$this, 'courseDeleteSucceeded'];
		$form->setRenderer(new Bs3FormRenderer);
		return $form;
	}

	public function courseFormSucceeded($form, $course_val){
		$cp_val['cpcode'] = $form->getHttpData($form::DATA_LINE, 'cpcode[]');
		$cp_val['cptype'] = $form->getHttpData($form::DATA_LINE, 'cptype[]');
		$cp_val['cpsect'] = $form->getHttpData($form::DATA_LINE, 'cpsect[]');
		$cp_val['cpchange'] = $form->getHttpData($form::DATA_LINE, 'cpchange[]');
		$cp_val['cpdata'] = $form->getHttpData($form::DATA_LINE, 'cpdata[]');
		if(! isset($this->courseid)) {
			$this->courseid = NULL;
		}
		$this->manager->putCourse($this->raceid, $this->courseid, $course_val, $cp_val);
		$this->redirect("Race:", $this->raceid);
	}

	public function courseDeleteSucceeded($form, $val){
		$this->manager->delCourse($this->raceid, $this->courseid);
		$this->redirect("Race:", $this->raceid);
	}

	public function renderAdd($raceid){
		$this->template->setFile(__DIR__. "/templates/Course/courseform.latte");
		$this->template->numcp = 4;
		$this->template->title = "Nová trať";
	}

	public function renderEdit($raceid, $courseid){
		$coursedata = $this->manager->getCourse($raceid, $courseid);
		$this->template->setFile(__DIR__. "/templates/Course/courseform.latte");
		$this->template->cp = $this->manager->getCourseCP($raceid, $courseid);
		$this->template->numcp = count($this->template->cp);
		$this['courseForm']->setDefaults($coursedata);
		Debugger::bardump($this->template->cp);
		$this->template->title = "Upravit trať";
	}

	public function renderDelete($raceid, $courseid){
		$this->template->course = $this->manager->getCourse($raceid, $courseid);
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
