<?php

namespace App\Presenters;

use Nette;
use Nette\Application\UI\Form;
use Nextras\Forms\Rendering\Bs3FormRenderer;
use Tracy\Debugger;

class CoursePresenter extends BaseRacePresenter
{

	const NUMCP_NEW = 5;

	/** @var \App\Model\Course */
	public $course;
	
	public function __construct(\App\Model\RaceManager $raceman, \App\Model\Course $course) {
		parent::__construct($raceman);
		$this->course = $course;
	}

	public function startup(){
		parent::startup();
		$this->course->setRace($this->raceid);
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
		$this->course->save($this->getParameter('courseid'), $course_val, $cp_val);
		$this->redirect("Course:list");
	}

	public function courseDeleteSucceeded($form, $val){
		$this->course->delete($this->getParameter('courseid'));
		$this->redirect("Course:list");
	}

	public function renderAdd(){
		$this->template->setFile(__DIR__. "/templates/Course/courseform.latte");
		$this->template->cps = [];
		for($i = 0; $i < self::NUMCP_NEW; $i++){
			$this->template->cps[] = [];
		}
		$this->template->title = "Nová trať";
	}

	public function renderEdit($courseid){
		$course = $this->course->load($courseid);
		$this->template->setFile(__DIR__. "/templates/Course/courseform.latte");
		$this['courseForm']->setDefaults($course);
		$this->template->cps = $this->course->loadCPs($course);
		$this->template->title = "Upravit trať";
	}

	public function renderDelete($courseid){
		$this->template->course = $this->course->load($courseid);
		$this->template->cps = $this->course->loadCPs($this->template->course);
	}

	public function renderList() {
		$this->template->raceid = $this->raceid;
		$this->template->race = $this->manager->getRaceInfo();
		$this->template->courses = $this->course->listAll();
	}

	public function formCancelled() {
		$this->redirect("Course:list");
	}

}
