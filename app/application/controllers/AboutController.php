<?php

class AboutController extends Zefir_Controller_Action
{
	protected function _getAbout()
	{
		$lang = new Application_Model_Languages();
		$lang->findLang($this->view->lang);
		if (array_key_exists(0, $lang->about))
		$about = $lang->about[0];
		else
		{
			$about = new Application_Model_About();
			$about->lang_id = $lang->lang_id;
		}
		 
		return $about;
	}


	public function init()
	{
		parent::init();
		$this->view->css = array(
				'simple/page.css'
		);
	}

	public function indexAction()
	{
		$about = $this->_getAbout();
		 
		$this->view->about = $about;
		$this->view->about_text = explode("\n", $about->about_text);
		$this->view->path = array(
		0 => array('route' => 'root', 'data' => array(), 'name' => array('main_page')),
		1 => array('route' => 'about', 'data' => array(), 'name' => array('about_link')),
		);

	}

	public function newAction()
	{
		$this->editAction();
		$this->render('edit');
	}

	public function editAction()
	{
		$form = new Application_Form_About();
		 
		$request = $this->getRequest();
		 
		$about = $this->_getAbout();
		 
		if ($request->isPost())
		{
			$data = $request->getPost();
			if (isset($data['leave']))
			{
				$this->flashMe('cancel_edit', 'SUCCESS');
				$this->_redirectToRoute(array('lang' => $this->view->lang), 'about');
			}
			if ($form->isValid($request->getPost()))
			{
				$about->populateFromForm($form->getValues());
				$about->save();
				 
				$this->flashMe('item_saved', 'SUCCESS');
				$this->_redirectToRoute(array('lang' => $this->view->lang), 'about');
			}
		}
		else
		{
			$form->populate($about->toArray());
		}
		 
		$this->view->form = $form;
	}


}

