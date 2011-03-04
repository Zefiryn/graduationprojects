<?php

class ApplicationsController extends Zefir_Controller_Action
{

    public function init()
    {
        parent::init();
    }

    public function indexAction()
    {
        // action body
    }

    public function newAction()
    {
    	$appSettings = Zend_Registry::get('appSettings');
        $form = new Application_Form_Application();
		$form->setDecorators(array(
			array('ViewScript', array('viewScript' => 'forms/_applicationForm.phtml'))
		));
		
		$request = $this->getRequest();
		
		if ($request->isPost())
		{//form has been submited
			
			if ($form->isValid($request->getPost()))
			{//form is valid
				
				$user = new Application_Model_Users();
				$user->populateFromForm($form->getValues());
				$user->save();
				
				if ($user->_user_id != null)
				{
					$application = new Application_Model_Applications();
					$application->populateFromForm($form->getValues());
					$application->_user = $user->_user_id;
					$application->save();
					
					var_dump($application);
					
				}
				
			}				
			else
			{//form has errors
				$form->getElement('edition')->setValue($appSettings->_current_edition->_edition_id);
			}	
		}
		else
		{//no form has been submited
			
			$form->getElement('edition')->setValue($appSettings->_current_edition->_edition_id);
		}
		
		$this->view->form = $form;
    }

    public function editAction()
    {
        // action body
    }

    public function deleteAction()
    {
        // action body
    }

    public function showAction()
    {
        // action body
    }

    public function updateAction()
    {
        // action body
    }


}











