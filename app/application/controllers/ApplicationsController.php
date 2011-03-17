<?php

class ApplicationsController extends Zefir_Controller_Action
{

    public function init()
    {
        parent::init();
    }

    public function indexAction()
    {

    	$sortForm = new Application_Form_ApplicationsSort();
    	
    	//get edition
    	$edition = new Application_Model_Editions();
    	$edition->getEditionByName(Zend_Registry::get('edition'));
    	
    	$sortFormData['edition'] = $edition->_edition_id;
    	$sortForm->populate($sortFormData);
    	
    	$this->view->sortForm = $sortForm;
    	
    	$application = new Application_Model_Applications();
    	$applications = $application->getApplications('edition', $edition->_edition_id);;
    	$this->view->applications = $applications;
    }

    public function newAction()
    {
    	$appSettings = Zend_Registry::get('appSettings');
    	$options = Zend_Registry::get('options');
        $form = new Application_Form_Application('new');
        $form->setAction('/applications/new');
		$form->setDecorators(array(
			array('ViewScript', array('viewScript' => 'forms/_applicationForm.phtml'))
		));
		
		$request = $this->getRequest();
		
		if ($request->isPost())
		{//form has been submited
			
			$form->populate($request->getPost());
			$form->getSubForm('user')->populate($request->getPost());
			
			if($form->leave->isChecked())
			{
				$this->_deleteCachedFiles($request->getPost());
				$this->_redirect('/index');	
			}
			
			//handle miniature file
			$form = $this->_checkMiniatureCache($form);
			$cached = $this->_checkFileCache('new');
			
			if ($form->isValid($request->getPost()))
			{//form is valid
				
				$this->_cacheFile($options['upload']['cache'], $form, 'miniature');
				$this->_handleFiles($form, $cached);
				
				if (!$form->getSubForm('file_1')->getElement('file_1')->hasErrors())
				{
					$user = new Application_Model_Users();
					$data = $form->getValues();
					$user->populateFromForm($data['user']);
					$user->save();
					
					if ($user->_user_id != null)
					{
						$application = new Application_Model_Applications();
						$application->populateFromForm($form->getValues());
						$application->_user = $user->_user_id;
						$application->save();
						
						$this->flashMe('application_added', 'SUCCESS');
						$this->_redirect('');
					}
				}
				
			}				
			else
			{//form has errors
				$form = $this->_cacheFile($options['upload']['cache'], $form, 'miniature');
				$form = $this->_handleFiles($form, $cached);
				
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
        $appSettings = Zend_Registry::get('appSettings');
    	$options = Zend_Registry::get('options');
        $form = new Application_Form_Application('edit');
        $form->setAction('/applications/edit');
		$form->setDecorators(array(
			array('ViewScript', array('viewScript' => 'forms/_applicationEditForm.phtml'))
		));
		
		$request = $this->getRequest();
		
		if ($request->isPost())
		{
			$id = $request->getParam('application_id', '');
			if ($this->user->_role != 'admin' 
				&& $this->user->_applications[0]->_application_id != $id)
			{
				$this->flashMe('not_allowed', 'FAILURE');
				$this->_redirect('/index');
			}
			
			
			$form->populate($request->getPost());
			if($form->leave->isChecked())
			{
				$this->_redirect('/applications');	
			}
			
			//handle miniature file
			$form = $this->_checkMiniatureCache($form, 'edit');
			$cached = $this->_checkFileCache('edit');
			
    		if ($form->isValid($request->getPost()))
    		{
    			$this->_cacheFile($options['upload']['cache'], $form, 'miniature', 'edit');
				$this->_handleFiles($form, $cached, 'edit');
    			if (!$form->getSubForm('file_1')->getElement('file_1')->hasErrors())
				{
					$application = new Application_Model_Applications();
					$application->populateFromForm($form->getValues());
					$application->save();
					
					$this->flashMe('application_edited', 'SUCCESS');
					$this->_redirectToRoute(array('id' => $application->_application_id), 'application');
				}
				
    		}
    		else
    		{
    			$this->_cacheFile($options['upload']['cache'], $form, 'miniature', 'edit');
    			$this->_handleFiles($form, $cached, 'edit');
    		}
		}
		else
		{
			$id = $request->getParam('id', '');
			
			//check privileges
			if ($this->user->_role != 'admin' 
				&& $this->user->_applications[0]->_application_id != $id)
			{
				$this->flashMe('not_allowed', 'FAILURE');
				$this->_redirect('/index');
			}
			
			//populate form
			if ($id != null)
			{
				$application = new Application_Model_Applications($id);
				if ($application->_application_id != null)
					$form->populate($application->prepareFormArray());
				else 
				{
					throw new Zend_Exception('Wrong id given');
				}
			}
			else
				throw new Zend_Exception('Wrong id given');	
		}
		
		$this->view->form = $form;
    }

    public function deleteAction()
    {
		$request = $this->getRequest();
		$id = $request->getParam('id', '');

    	if ($this->user->_role != 'admin' 
    		&& $this->user->_applications[0]->_application_id != $id)
		{
			$this->flashMe('not_allowed', 'FAILURE');
			$this->_redirect('/index');
		}
		$application = new Application_Model_Applications($id);
		$application->delete();
		$this->flashMe('application_deleted', 'SUCCESS');
		$this->_redirect('applications');
    }

    public function showAction()
    {
		$request = $this->getRequest();
		
		$id = $request->getParam('id', '');
		
		if ($id == null)
			throw new Zend_Exception('Wrong id parameter');
		
			
		if ($this->user->_role == 'admin' 
			|| $this->user->_applications[0]->_application_id == $id)
		{
			$application = new Application_Model_Applications($id);
			$this->view->application = $application;
		}
		else
		{
			$this->flashMe('not_allowed', 'FAILURE');
			$this->_redirect('/index');
		}
    }

	protected function _checkMiniatureCache($form)
    {
    	$request = $this->getRequest();
    	$options = Zend_Registry::get('options');
    	
    	$miniatureCache = $request->getParam('miniatureCache', '');
		$miniatureFile = APPLICATION_PATH.'/../public/assets/'.$miniatureCache;

		if ($miniatureCache != null	&& file_exists($miniatureFile))
			$form->getElement('miniature')->setRequired(FALSE);
		else
			$form->getElement('miniatureCache')->setValue(null);
		
		return $form;
    }

    protected function _checkFileCache($type = 'new')
    {
    	$params = $this->getRequest()->getParams();
    	$options = Zend_Registry::get('options');
    	$appSettings = Zend_Registry::get('appSettings');
    	$cached = FALSE;
    	
    	for($i = 1; $i <= $appSettings->_max_files; $i++)
    	{
    		$fileCache = $params['file_'.$i]['file_'.$i.'Cache'];
    		if ($type == 'new')
    			$file = APPLICATION_PATH.'/../public'.$options['upload']['cache'].'/'.$fileCache;
    		else
    			$file = APPLICATION_PATH.'/../public'.$options['upload']['applications'].'/'.$fileCache;
    		if ($fileCache != null && file_exists($file))
    			$cached = TRUE;
    	}

    	return $cached;
    }
    
    protected function _handleFiles($form, $cached, $type = 'new')
    {
    	$appSettings = Zend_Registry::get('appSettings');
    	$options  = Zend_Registry::get('options');
    	$newFile = FALSE; 
    	for($i = 1; $i <= $appSettings->_max_files; $i++)
		{
			$sf = $form->getSubForm('file_'.$i);
			$sf = $this->_cacheFile($options['upload']['cache'], $sf, 'file_'.$i, 'edit');
			if ($sf->getElement('file_'.$i.'Cache')->getValue() != null)
			{
				$sf->getElement('file_'.$i)->setLabel('new_file');
				$newFile = TRUE;
			}
		}
		
		if (!$cached && !$newFile)
		{//add error to the first file field as there are no cached nor new uploaded files
			$form->getSubForm('file_1')->getElement('file_1')->addError($this->view->translate('fileUploadErrorNoFile'));
		}
		
		return $form;
    }
    
    protected function _deleteCachedFiles($data)
    {
    	$dir = APPLICATION_PATH.'/../public/assets/';
    	
    	if ($data['miniatureCache'] != null && file_exists($dir.$data['miniatureCache']))
    	{
    		unlink($dir.$data['miniatureCache']);
    	}
    	
    	for($i = 1; $i <= 10; $i++)
    	{
    		if ($data['file_'.$i]['file_'.$i.'Cache'] != null &&
    			file_exists($dir.$data['file_'.$i]['file_'.$i.'Cache']))
    		{
    			unlink($dir.$data['file_'.$i]['file_'.$i.'Cache']);
    		}
    	}
    }
}
