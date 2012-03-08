<?php

class PressController extends Zefir_Controller_Action
{

	public function init()
	{
		parent::init();
		$this->view->css = array(
			'simple/press.css'
		);
	}
	
	public function indexAction()
	{
		$appSettings = Zend_Registry::get('appSettings');
		$press = new Application_Model_Press();
		$this->view->press = $press->getAllFiles();
	}
	
	public function newAction()
	{
		$form = new Application_Form_Press();
		$form->setDecorators(array(
				array('ViewScript', array('viewScript' => 'forms/_pressForm.phtml'))
		));
		
		if ($this->_request->isPost())
		{
			if ($this->_request->getPost('leave', false))
			{
				$this->flashMe('cancel_edit', 'SUCCESS');
				$this->_redirectToRoute(array(), 'press');
			}
			elseif ($form->isValid($this->_request->getPost()) || count($form->getMessages()) == 0)
			{
				$press_element = new Application_Model_Press();
				$press_element->populate($form->getValues());
				$this->_saveFile($press_element, $form->getElement('element_path')->getDestination());
				$press_element->save();
				$this->flashMe('press_added');
				$this->_redirectToRoute(array(), 'press');
			}
		}
		$this->view->form = $form;
	}
	
	public function editAction()
	{
		$id = $this->_request->getParam('id', null);
		
		if ($id) {
			$press = new Application_Model_Press($id);
			$form = new Application_Form_Press();
			$form->getElement('element_path')->setRequired(false);
			$form->setDecorators(array(
					array('ViewScript', array('viewScript' => 'forms/_pressForm.phtml'))
			));
		
			if ($this->_request->isPost())
			{
				if ($this->_request->getPost('leave', false))
				{
					$this->flashMe('cancel_edit', 'SUCCESS');
					$this->_redirectToRoute(array(), 'press');
				}
				elseif ($form->isValid($this->_request->getPost()) || count($form->getMessages()) == 0)
				{
					$press_element = new Application_Model_Press();
					$press_element->populate($form->getValues());
					$this->_saveFile($press_element, $form->getElement('element_path')->getDestination());
					$press_element->save();
					$this->flashMe('press_edited');
					$this->_redirectToRoute(array(), 'press');
				}
			}
			else 
			{
				$form->populate($press->prepareFormArray());
			}
		$this->view->form = $form;
		}
	}
	 
	public function descriptionAction()
	{
		$form = new Application_Form_Press_Description();
		
		$press = new Application_Model_Press();
		$press->findDescription();
		if ($this->_request->isPost())
		{
			if ($this->_request->getPost('leave', false))
			{
				$this->flashMe('cancel_edit', 'SUCCESS');
				$this->_redirectToRoute(array(), 'press');
			}
		}
		else 
		{
			$form->populate($press->prepareDescriptionFormArray());
		}
		
		$this->view->form = $form;
	}
	
	public function sortAction()
	{
		$request = $this->getRequest();
		$element_id = $request->getParam('id', null);
		$new_position = $request->getParam('position', 1);
		$elements = new Application_Model_Press();
			
		$position = 1;
		foreach ($elements->fetchAll() as $element)
		{
			if ($element->element_id != $element_id && $position < $new_position)
			{
				$element->position = $position ;
				$element->save();
			}
			elseif ($element->element_id != $element_id && $position  > $new_position)
			{
				$element->position = $position + 1;
				$element->save();
			}
			elseif ($element->element_id != $element_id && $position  == $new_position)
			{
				$element->position = $position + 1;
				$element->save();
			}
			elseif ($element->element_id == $element_id)
			{
				$element->position = $new_position;
				$element->save();
				$position--;	//reduce position so next paragraphs would fill the place
			}
			$position++;
		}
			
		$this->_helper->viewRenderer->setNoRender(true);
	}
	
	public function deleteFileAction()
	{
		$this->_helper->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
		
		if ($this->_request->isXmlHttpRequest())
		{
			$id = $this->_request->getParam('id');
			if (ctype_digit($id)) 
			{
				$pressFile = new Application_Model_PressFiles($id);
				$press = $pressFile->press;
				if (!$pressFile->isEmpty())
				{
					$pressFile->delete();
					if (count($press->files) == 0)
					{
						$press->delete();
					}
					echo $this->_helper->json(array("file_id" => $id));
				}
				else
				{
					echo $this->_helper->json(array("access" => 0), FALSE);
				}
			}
		}
		else 
		{
			$this->flashMe('ajax_only', 'FAILURE');
			$this->_redirectToRoute(array(), 'press');
		}
	}


	protected function _saveFile($press, $cache)
	{
		$folder = $press->getUploadDir();
		$element_files = array();
		foreach($_FILES as $file)
		{
			$filename = ($file['name']);
			$cache_file = $cache . '/'.$filename;
			if (!file_exists($cache_file)) 
			{
				$cache_file = $file['tmp_name'];
			}
			
			//make filename unique
			$ext = Zefir_Filter::getExtension($filename);
			$name = substr($filename, 0, strpos($filename, $ext));
			$filename = substr($name,0,130).time().'.'.$ext;
			
			if (rename($cache_file, $folder.'/'.$filename)) 
			{
				$press_file = new Application_Model_PressFiles();
				$press_file->path = $filename;
				$press->addChild($press_file, 'files');
			}
		}
		return $press;
	}
}