<?php

namespace App\Presenters;

use Nette;
use App\Forms\SignFormFactory;
use Tracy\Debugger;

class SignPresenter extends BasePresenter
{
	/** @var SignFormFactory */
	private $factory;

	public function injectSignFormFactory(SignFormFactory $service) {
		$this->factory = $service;
	}

	/**
	 * Sign-in form factory.
	 * @return Nette\Application\UI\Form
	 */
	protected function createComponentSignInForm()
	{
		$form = $this->factory->create();
		$form->onSuccess[] = function (\Nette\Application\UI\Form $form) {
			$this->redirect('Main:');
		};
		return $form;
	}

	public function actionOut()
	{
		$this->getUser()->logout();
		$this->flashMessage('You have been signed out.');
		$this->redirect('Main:');
	}
	
}
