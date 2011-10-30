<?php

class SettingsController extends Zefir_Controller_Action
{

    public function init()
    {
        parent::init();
    }
    
	public function editAction()
	{
		$request = $this->getRequest();
		$appSettings = Zend_Registry::get('appSettings');
		$form = new Application_Form_Settings();
		
		if ($request->isPost())
		{
			$form->populate($request->getPost());
			
			if($form->leave->isChecked())
			{
				$this->flashMe('cancel_edit');
				$this->_redirectToRoute(array(), 'root');	
			}
			
    		elseif ($form->isValid($request->getPost()))
    		{
    			$settings = new Application_Model_Settings();
    			$settings->populateFromForm($form->getValues());
    			$settings->save();
    			$this->flashMe('settings_saved', 'SUCCESS');
    			$this->_redirectToRoute(array(), 'root');
    		}
			
		}
		else
		{
			$form->populate($appSettings->prepareFormArray());
		}
		
		$this->view->form = $form;
	}
}

