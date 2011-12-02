<?php

class ApplicationsController extends Zefir_Controller_Action
{

	public function init()
	{
		parent::init();
	}

	public function indexAction()
	{
		Zefir_Pqp_Classes_Console::logSpeed('start action');
		
		$application = new Application_Model_Applications();
		$applications = $application->getApplications($this->_getSort());
		
		$this->view->statistics = $this->_createStatistics($applications);
		$this->view->applications = $applications;
	}

	public function newAction()
	{
		if (!$this->_checkDeadline())
		{
			$this->flashMe('application_deadline_has_passed');
			$this->_redirectToRoute(array(), 'root');
		}
		$appSettings = Zend_Registry::get('appSettings');
		$options = Zend_Registry::get('options');

		$form = new Application_Form_Application('new');
		$form->setAction($this->view->url(array(), 'application_new'));
		$form->setDecorators(array(
		array('ViewScript', array('viewScript' => 'forms/_applicationForm.phtml'))
		));

		$session = new Zend_Session_Namespace('applicationForm');
		if (isset($session->form))
		{
			$values =  $session->form;
			$form->populate($values);
		}
		 
		$request = $this->getRequest();

		if ($request->isPost())
		{
			//form has been submited
				
			if($request->getParam('leave', null))
			{
				$this->_log('leaving');
				$this->_deleteCachedFiles($request->getPost());
				$this->flashMe('cancel_edit');
				$this->_redirectToRoute(array(), 'root');
			}
				
			$cached = $this->_checkFileCache('new');
				
			if ($form->isValid($request->getPost()) || count($form->getMessages()) == 0)
			{
				//form is valid

				$this->_handleFiles($form, $cached);

				if (!$form->getSubForm('file_1')->getElement('file_1')->hasErrors())
				{
					$form = $this->_createFileOrder($form);
					$session->form = $form->getValues();
					$this->view->form = $form;
					$this->view->path = array(
					0 => array('route' => 'root', 'data' => array(), 'name' => array('main_page')),
					1 => array('route' => 'lang_application_new', 'data' => array('lang' => $this->view->lang), 'name' => array('form_link')),
					);
					$this->renderScript('applications/confirm.phtml');
				}

			}
			else
			{//form has errors
				$form = $this->_handleFiles($form, $cached);
				$this->_log('Request:');
				$this->_log($this->getRequest()->getParams());
				$this->_log('Form values:');
				$this->_log($form->getValues());
			}
		}
		else
		{//no form has been submited

			$form->getElement('edition_id')->setValue($appSettings->edition->edition_id);
				
		}

		$form = $this->_createFileOrder($form);
		$this->view->form = $form;
		$this->view->path = array(
		0 => array('route' => 'root', 'data' => array(), 'name' => array('main_page')),
		1 => array('route' => 'lang_application_new', 'data' => array('lang' => $this->view->lang), 'name' => array('form_link')),
		);
	}

	public function saveAction()
	{
		$request = $this->getRequest();
		 
		if ($request->getParam('confirm'))
		{
			//add application
			$session = new Zend_Session_Namespace('applicationForm');
			$user = new Application_Model_Users();
			$data = $session->form;
			$user->populateFromForm($data['user']);
			$user->save();

			if ($user->user_id != null)
			{
				$application = new Application_Model_Applications();
				$application->populateFromForm($session->form);
				$application->user_id = $user->user_id;
				$application->save();
				unset($session->form);
				$this->_sendConfirmationMail($user);
				$this->flashMe('application_added', 'SUCCESS');
				$this->_redirectToRoute(array(), 'root');
				$this->render('delete');
			}
		}
		else
		{
			$this->_redirectToRoute(array(), 'application_new');
		}
	}

	public function editAction()
	{
		if (!$this->_checkDeadline())
		{
			$this->flashMe('application_deadline_has_passed');
			$this->_redirectToRoute(array(), 'root');
		}
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

		if ($this->view->user->_role != 'admin'
			&& $this->user->applications[0]->application_id != $id)
		{
			$this->flashMe('not_allowed', 'FAILURE');
			$this->_redirect('/index');
		}
		$application = new Application_Model_Applications($id);
		$application->delete();
		
		$this->_helper->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
		$this->flashMe('application_deleted', 'SUCCESS');		
		echo json_encode(array('link' => $this->view->url(array(), 'applications')));
		
		
		
	}

	public function showAction()
	{
		$request = $this->getRequest();

		$id = $request->getParam('id', '');

		if ($id == null)
		throw new Zend_Exception('Wrong id parameter');

		$application = new Application_Model_Applications($id);
			
		if ($this->view->user->role == 'admin'
		|| $this->view->user->role == 'juror'
		|| $this->view->user->user_id == $application->user->user_id)
		{
				
			$this->view->application = $application;
			$this->view->adjacent = $application->getAdjacentApplication();
		}
		else
		{
			$this->flashMe('not_allowed', 'FAILURE');
			$this->_redirect('/index');
		}
	}

	public function deleteImageAction()
	{
		$request = $this->getRequest();
		if ($request->isXmlHttpRequest())
		{
			$id = $request->getParam('id');

			if (ctype_digit($id))
			{
				//already sent image
				$file = new Application_Model_Files($id);
				if ($this->view->user->user_id == $file->application->user->user_id || $this->view->user->role == 'admin')
				{
					$file->delete();
					$this->_helper->json(array("file_id" => $id));
				}
				else
				{
					$this->_helper->json(array("access" => 0), FALSE);
				}
			}
			else
			{//cached image
				$path = APPLICATION_PATH.'/../public/assets/cache/'.$id;
				unlink($path);
				$this->_helper->json(array("file_d" => $id), FALSE);
			}
		}
		else
		{
			$this->flashMe('ajax_only', 'FAILURE');
			$this->_redirectToRoute(array(), 'root');
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
			$fileCache = isset($params['file_'.$i]['file_'.$i.'Cache']) ?
			$params['file_'.$i]['file_'.$i.'Cache'] : null;
				
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
			$sf = $this->_cacheFile($options['upload']['cache'], $sf, 'file_'.$i);
			if ($sf->getElement('file_'.$i.'Cache')->getValue() != null)
			{
				$newFile = TRUE;
			}
		}

		if (!$cached && !$newFile)
		{
			//add error to the first file field as there are no cached nor new uploaded files
			$form->getSubForm('file_1')->getElement('file_1')->addError($this->view->translate('fileUploadErrorNoFile'));
		}

		return $form;
	}

	protected function _createFileOrder($form)
	{
		$present = array();
		$empty = array();
		$appSettings = Zend_Registry::get('appSettings');
		for($i = 1; $i <= $appSettings->max_files; $i++)
		{
			if ($form->getSubForm('file_'.$i)->getElement('file_'.$i.'Cache')->getValue() != null)
			$present[] = 'file_'.$i;
			else
			$empty[] = 'file_'.$i;
		}

		$order = array_merge($present, $empty);
		$form->fileOrder = $order;
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
		Zefir_Pqp_Classes_Console::logSpeed('start statistics');
		$work_type = new Application_Model_WorkTypes();
		$types = $work_type->getWorkTypes();
		unset($types[0]);

		$languages = array('cs', 'pl', 'sk', 'hu', 'all');
		 
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
		Zefir_Pqp_Classes_Console::logSpeed('end statistics');
		return $statistics;
	}

	protected function _getSort()
	{
		$request = $this->getRequest();
		$sortApplication = new Zend_Session_Namespace('app_sort');
		 
		$currentSort = $request->getParam('sort', NULL);
		$lastSort = $sortApplication->sort != null ? $sortApplication->sort : 'surname';
		 
		if (strstr($lastSort, $currentSort))
		{
			$order = substr($lastSort, strpos($lastSort, ' ')+1);
			$currentSort .= $order == 'ASC' ? ' DESC' : ' ASC';
		}
		elseif ($currentSort != NULL)
		{
			$currentSort .= ' ASC';
		}
		else
		{
			$currentSort = 'surname ASC';
		}
		//save current sort
		$sortApplication->sort = $currentSort;
		return $currentSort;
	}


	protected function _sendConfirmationMail($user)
	{
		$appSettings = Zend_Registry::get('appSettings');
		$mail = new Zend_Mail('UTF8');
		 
		$mail->setFrom('no-reply@2plus3d.pl', $this->view->translate('confirmation_email_from'));
		$mail->addTo($user->email, $user->getUserFullName());
		$mail->setSubject($this->view->translate('confirmation_email_subject'));

		$body = $this->view->translate('confirmation_email_body');
		$body = sprintf($body, date($appSettings->date_format, $appSettings->application_deadline), $user->nick);
		$mail->setBodyText($body);
		$mail->send();
	}

	protected function _checkDeadline()
	{
		$appSettings = Zend_Registry::get('appSettings');
		 
		if ($appSettings->application_deadline >= time() || $this->view->user->_role == 'admin')
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
}
