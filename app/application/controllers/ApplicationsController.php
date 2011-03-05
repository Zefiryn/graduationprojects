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
    	$options = Zend_Registry::get('options');
        $form = new Application_Form_Application();
		$form->setDecorators(array(
			array('ViewScript', array('viewScript' => 'forms/_applicationForm.phtml'))
		));
		
		$request = $this->getRequest();
		
		if ($request->isPost())
		{//form has been submited

			//handle miniature file
			$form = $this->_checkMiniatureCache($form);
			
			
			if ($form->isValid($request->getPost()))
			{//form is valid
				
				$form =  $this->_cacheFile($options['upload']['cache'], $form, 'miniature');
				
				$user = new Application_Model_Users();
				$user->populateFromForm($form->getValues());
				$user->save();
				
				if ($user->_user_id != null)
				{
					$application = new Application_Model_Applications();
					$application->populateFromForm($form->getValues());
					$application->_user = $user->_user_id;
					$application->save();
					
					$this->flashMe('application_added', 'SUCCESS');
					$this->_redirect('', $options);
				}
				
			}				
			else
			{//form has errors
				
				$form =  $this->_cacheFile($options['upload']['cache'], $form, 'miniature');
				if ($form->getElement('miniatureCache')->getValue() != null)
				{
					$form->getElement('miniature')->setLabel('new_miniature');
				}
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
    
    protected function _checkMiniatureCache($form)
    {
    	$miniatureCache = $request->getParam('miniatureCache', '');
    	$miniatureFile = APPLICATION_PATH.'/../public'.$options['upload']['cache'].'/'.$miniatureCache;

    	if ($miniatureCache != null	&& file_exists($miniatureFile))
			$form->getElement('miniature')->setRequired(FALSE);
		else
			$form->getElement('miniatureCache')->setValue(null);
		
		return $form;
    }

}
