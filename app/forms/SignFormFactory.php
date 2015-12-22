<?php

namespace App\Forms;

use Nette;
use Nette\Application\UI\Form;
use Nette\Security\User;
use Nextras\Forms\Rendering\Bs3FormRenderer;


class SignFormFactory extends Nette\Object
{
	/** @var User */
	private $user;


	public function __construct(User $user)
	{
		$this->user = $user;
	}


	/**
	 * @return Form
	 */
	public function create()
	{
		$form = new Form;

		$form->addGroup();
		$form->addText('username', 'Login:')
			->setRequired('Please enter your username.');
		$form->addPassword('password', 'Heslo:')
			->setRequired('Please enter your password.');

		$form->addCheckbox('remember', 'Zůstat přihlášen');

		$form->addGroup();
		$form->addSubmit('send', 'Přihlásit');
		
		$form->addSubmit('cancel', 'Zpět')
			->setValidationScope(FALSE);
						
		$form->setDefaults(array(
		    'username' => 'user',
		    'password' => NULL,
		    'remember' => TRUE
		));
		
		$form->onSuccess[] = array($this, 'formSucceeded');
		$form->setRenderer(new Bs3FormRenderer);
		return $form;
	}

	public function formSucceeded(Form $form, $values)
	{
		if ($values->remember) {
			$this->user->setExpiration('14 days', FALSE);
		} else {
			$this->user->setExpiration('20 minutes', TRUE);
		}
		if($form->isSubmitted()->getName() != 'cancel'){
			try {
				$this->user->login($values->username, $values->password);
			} catch (Nette\Security\AuthenticationException $e) {
				$form->addError($e->getMessage());
			}
		}
	}
}
