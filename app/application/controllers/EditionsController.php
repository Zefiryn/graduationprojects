<?php

class EditionsController extends Zefir_Controller_Action
{

	public function init()
	{
		parent::init();
		$this->view->css = array(
				'simple/forms.css'
		);
	}

	public function indexAction()
	{
		$edition = new Application_Model_Editions();
		$this->view->editions = $edition->getEditions('ASC', false);
	}

	public function showAction()
	{
		$edition = new Application_Model_Editions();
		$this->view->editions = $edition->getEditions('ASC', false);
		$this->render('index');
	}


	public function newAction()
	{
		$request = $this->getRequest();
		$form = new Application_Form_Edition('new');
		$form->setAction($this->view->baseUrl('editions/new'));


		if ($request->isPost())
		{
			$form->populate($request->getPost());
			if($form->leave->isChecked())
			{
				$this->flashMe('adding_left', 'SUCCESS');
				$this->_redirect('/editions');
			}
				
			elseif ($form->isValid($request->getPost()))
			{
				$edition = new Application_Model_Editions();
				$edition->populate($form->getValues());
				$edition->save();
				 
				$this->flashMe('edition_edited', 'SUCCESS');
				$this->_redirect('/editions');
			}
		}

		$this->view->form = $form;
	}

	public function editAction()
	{
		$request = $this->getRequest();
		$id = $request->getParam('id', '');
		$edition = new Application_Model_Editions($id);
		$form = new Application_Form_Edition('edit');
		$form->setAction($this->view->baseUrl('editions/edit'));


		if ($request->isPost())
		{
			$form->populate($request->getPost());
			if($form->leave->isChecked())
			{
				$this->flashMe('edition_left', 'SUCCESS');
				$this->_redirect('/editions');
			}
				
			elseif ($form->isValid($request->getPost()))
			{
				$edition->populate($form->getValues());
				$edition->save();
				 
				$this->flashMe('edition_edited', 'SUCCESS');
				$this->_redirect('/editions');
			}
		}
		else
		{
			$form->populate($edition->prepareFormArray());
		}
		$this->view->form = $form;
	}

	public function deleteAction()
	{
		$request = $this->getRequest();
		$id = $request->getParam('id', '');

		$edition = new Application_Model_Editions($id);
		$edition->delete();
		$this->flashMe('edition_deleted', 'SUCCESS');
		$this->_redirectToRoute(array(), 'editions');
	}

	public function archiveAction()
	{
		$request = $this->getRequest();
		$id = $request->getParam('id', null);
		
		if ($id) 
		{
			if ($request->getParam('apps', null))
			{
				$apps = $request->getParam('apps');
				$application = new Application_Model_Applications();
				$results = $application->archive($apps);
				if ($results['hasError'] == true) 
				{
					unset($results['hasError']);
					foreach($results as $result)
					{
						if (isset($result['object'])) $result['object']->delete();
					}
					$results['hasError'] = true;
				}
				$this->_helper->layout()->disableLayout();
				$this->_helper->viewRenderer->setNoRender(true);
				echo Zend_Json::encode($results);
			}
			else 
			{
				$application = new Application_Model_Applications();
				$applications = $application->getWinningApps($id);
				$this->view->applications = $applications; 
				$this->view->unselected_apps = $application->getRemainedApps(array_keys($applications));
			}
		}
	}
	
	public function publishAction()
	{
		$this->_helper->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
		$id  = $this->_request->getParam('edition_id', null);
		
		if ($id)
		{
			$edition = new Application_Model_Editions($id);
			$info = $edition->publishResults();
		}
		
		echo json_encode(array($info));
	}

}

