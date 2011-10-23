<?php

class ApplicationsController extends Zefir_Controller_Action
{

    public function init()
    {
        parent::init();
    }

    public function indexAction()
    {
    	//get edition
    	$edition = new Application_Model_Editions();
    	$edition->getEditionByName(Zend_Registry::get('edition'));
    	
    	$application = new Application_Model_Applications();
    	$applications = $application->getApplications($edition->edition_id, $this->_getSort());
    	$this->view->statistics = $this->_createStatistics($applications);
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
			
			//$form->getSubForm('user')->populate($request->getPost());
			
			if($request->getParam('leave', null))
			{
				$this->_deleteCachedFiles($request->getPost());
				$this->_redirectToRoute(array(), 'root');	
			}
			
			$cached = $this->_checkFileCache('new');
			
			if ($form->isValid($request->getPost()) || count($form->getMessages()) == 0)
			{//form is valid
				
				$this->_handleFiles($form, $cached);
				
				if (!$form->getSubForm('file_1')->getElement('file_1')->hasErrors())
				{
					$user = new Application_Model_Users();
					$data = $form->getValues();
					$user->populateFromForm($data['user']);
					$user->save();

					//if ($user->user_id != null)
					{
						$application = new Application_Model_Applications();
						$application->populateFromForm($form->getValues());
						$application->user = $user;
						//$application->save();
						
						var_dump($application);
						//$this->flashMe('application_added', 'SUCCESS');
						//$this->_redirect('/');
					}
				}
				
			}				
			else
			{//form has errors
				$form = $this->_handleFiles($form, $cached);
			}	
		}
		else
		{//no form has been submited

			$form->getElement('edition_id')->setValue($appSettings->edition->edition_id);
			
		}
		
		$this->view->form = $form;
		$this->view->path = array(
			0 => array('route' => 'root', 'data' => array(), 'name' => array('main_page')),
    		1 => array('route' => 'lang_application_new', 'data' => array('lang' => $this->view->lang), 'name' => array('form_link')),
		);
    }

    public function editAction()
    {
        $appSettings = Zend_Registry::get('appSettings');
    	$options = Zend_Registry::get('options');
        $form = new Application_Form_Application('edit');
        $form->removeElement('personal_data_agreement');
        $form->setAction('/applications/edit');
		$form->setDecorators(array(
			array('ViewScript', array('viewScript' => 'forms/_applicationEditForm.phtml'))
		));
		
		$request = $this->getRequest();
		
		if ($request->isPost())
		{
			$id = $request->getParam('application_id', '');
			if ($this->view->user->_role != 'admin'
				&& !isset($this->view->user->applications[0]) 
				&& $this->view->user->applications[0]->application_id != $id)
			{
				$this->flashMe('not_allowed', 'FAILURE');
				$this->_redirect('/index');
			}
			
			
			if($request->getParam('leave', null))
			{
				$this->_redirectToRoute(array(), 'applications');	
			}
			
			$cached = $this->_checkFileCache('edit');
			
    		if ($form->isValid($request->getPost()) || count($form->getMessages()) == 0)
    		{
    			$this->_handleFiles($form, $cached, 'edit');
    			if (!$form->getSubForm('file_1')->getElement('file_1')->hasErrors())
				{
					$application = new Application_Model_Applications();
					$application->populateFromForm($form->getValues());
					$application->save();
					
					$this->flashMe('application_edited', 'SUCCESS');
					$this->_redirectToRoute(array('id' => $application->application_id), 'application');
				}
				
    		}
    		else
    		{
    			var_dump($form->getMessages());
    			var_dump($form->getValues());
    			$this->_handleFiles($form, $cached, 'edit');
    		}
		}
		else
		{
			$id = $request->getParam('id', '');
			
			if ($this->view->user->_role != 'admin' 
				&& !isset($this->view->user->applications[0])
				&& $this->view->user->applications[0]->application_id != $id)
			{
				//$this->flashMe('not_allowed', 'FAILURE');
				//$this->_redirect('/index');	
			}
			
			//populate form
			if ($id != null)
			{
				$application = new Application_Model_Applications($id);
				if ($application->application_id != null)
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
    		&& $this->user->applications[0]->application_id != $id)
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
			|| $this->user->_role == 'juror'
			|| $this->user->applications[0]->application_id == $id)
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

	protected function _checkFileCache($type = 'new')
    {
    	$params = $this->getRequest()->getParams();
    	$options = Zend_Registry::get('options');
    	$appSettings = Zend_Registry::get('appSettings');
    	$cached = FALSE;
    	
    	for($i = 1; $i <= $appSettings->max_files; $i++)
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
    	for($i = 1; $i <= $appSettings->max_files; $i++)
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
    	
    	for($i = 1; $i <= 10; $i++)
    	{
    		if ($data['file_'.$i]['file_'.$i.'Cache'] != null &&
    			file_exists($dir.$data['file_'.$i]['file_'.$i.'Cache']))
    		{
    			unlink($dir.$data['file_'.$i]['file_'.$i.'Cache']);
    		}
    	}
    }
    
    protected function _createStatistics($applications)
    {
    	$work_type = new Application_Model_WorkTypes();
    	$types = $work_type->getWorkTypes();
    	unset($types[0]);

    	$languages = array('cz', 'pl', 'sk', 'all');
    	
    	$statistics = array();
    	
    	foreach($languages as $language)
    	{
    		foreach($types as $type)
    		{
    			$statistics[$language][$type] = 0;	
    		}
    		$statistics[$language]['all'] = 0;
    	}
    	
    	foreach ($applications as $application)
    	{
    		$statistics['all'][$application->work_type->work_type_name]++;
    		$statistics['all']['all']++;
    		$statistics[$application->country][$application->work_type->work_type_name]++;
    		$statistics[$application->country]['all']++;
    	}
    	
    	return $statistics;
    }
    
    protected function _getSort()
    {
    	$request = $this->getRequest();
    	$sortApplication = new Zend_Session_Namespace('app_sort');
    	
    	$currentSort = $request->getParam('sort', NULL);
    	$lastSort = $sortApplication->sort;
    	
    	if (strstr($lastSort, $currentSort))
    	{
    		$order = substr($lastSort, strpos($lastSort, ' ')+1);
    		$currentSort .= $order == 'ASC' ? ' DESC' : ' ASC';
    	}
    	elseif ($currentSort != NULL)
    		$currentSort .= ' ASC';
    	
    	//save current sort
    	$sortApplication->sort = $currentSort;
    	
    	return $currentSort;
    }
}
