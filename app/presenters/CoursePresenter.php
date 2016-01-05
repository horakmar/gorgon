<?php

namespace App\Presenters;

use Nette;
use Nette\Application\UI\Form;
use Nextras\Forms\Rendering\Bs3FormRenderer;
use Tracy\Debugger;

class CoursePresenter extends BaseRacePresenter
{

	/** @persistent */
	public $courseid = NULL;

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
		$this->manager->putCourse($this->courseid, $course_val, $cp_val);
		$this->redirect("Course:list");
	}

	public function courseDeleteSucceeded($form, $val){
		$this->manager->delCourse($this->courseid);
		$this->redirect("Course:list");
	}

	public function renderAdd(){
		$this->template->setFile(__DIR__. "/templates/Course/courseform.latte");
		$this->template->numcp = 4;
		$this->template->title = "Nová trať";
	}

	public function renderEdit($courseid){
		$coursedata = $this->manager->getCourse($courseid);
		$this->template->setFile(__DIR__. "/templates/Course/courseform.latte");
		$this->template->cp = $this->manager->getCourseCP($courseid);
		$this->template->numcp = count($this->template->cp);
		$this['courseForm']->setDefaults($coursedata);
		Debugger::bardump($this->template->cp);
		$this->template->title = "Upravit trať";
	}

	public function renderDelete($courseid){
		$this->template->course = $this->manager->getCourse($courseid);
		$this->template->cps = $this->manager->getCourseCP($courseid);
	}

	public function renderList() {
		$this->template->raceid = $this->raceid;
		$this->template->race = $this->manager->getRaceInfo();
		$this->template->courses = $this->manager->listCourses();
	}

	public function formCancelled() {
		$this->redirect("Course:list");
	}

}
