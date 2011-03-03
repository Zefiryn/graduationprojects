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
        $form = new Application_Form_Application();
		$form->setDecorators(array(
						array('ViewScript', array('viewScript' => 'forms/_applicationForm.phtml'))
		));
		$this->view->form = $form;
		
		$request = $this->getRequest();
		
		if ($request->isPost())
		{
			if ($form->isValid($request->getPost()))
			{}				
			else
			{
				var_dump($form->getValues());
				var_dump($form->getErrors());
			}	
		}
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











