<?php

class RegulationsController extends Zefir_Controller_Action
{
	protected $_regulation;

	protected function _getRegulations($refresh = FALSE)
	{
		if ($this->_regulation == null || $refresh)
		{
			$lang = new Application_Model_Languages();
			$lang->findLang($this->view->lang);
			$this->_regulation = $lang->regulations;
		}

		return $this->_regulation;
	}

	public function init()
	{
		parent::init();
		$this->view->css = array(
				'simple/page.css', 'simple/forms.css'
		);
	}

	public function indexAction()
	{
		$regulations = $this->_getRegulations();

		$columns = array('left' => array(), 'right' => array());
		foreach($regulations as $i => $regulation)
		{
			if ($i < count($regulations)/2)
			$columns['left'][$i] = $regulation;
			else
			$columns['right'][$i] = $regulation;
		}


		$this->view->columns = $columns;
		$this->view->path = array(
		0 => array('route' => 'root', 'data' => array(), 'name' => array('main_page')),
		1 => array('route' => 'regulation', 'data' => array(), 'name' => array('regulation_link')),
		);

		if ($this->view->user->role == 'admin' || $this->view->user->role == 'juror')
			$this->render('index');
		else
			$this->render('show');

	}

	public function newAction()
	{
		$request = $this->getRequest();
		$form = new Application_Form_Regulations_Paragraph();
		 
		if ($request->isPost())
		{
			$data = $request->getPost();
			if (isset($data['leave']))
			{
				$this->flashMe('cancel_edit', 'SUCCESS');
				$this->_redirectToRoute(array('lang' => $this->view->lang), 'regulation');
			}
			else
			{
				if ($form->isValid($request->getPost()))
				{
					$paragraph = new Application_Model_Regualtions();
					$paragraph->populateFromForm($form->getValues());
						
					$lang = new Application_Model_Languages();
					$lang->findLang($this->view->lang);
					$paragraph->lang_id = $lang->lang_id;
						
					$paragraph->positionLast();
						
					$paragraph->save();
					$this->flashMe('paragraph_saved');
					$this->_redirectToRoute(array(), 'regulation');
				}
			}
		}
		 
		$this->view->form = $form;
		 
	}

	public function editAction()
	{
		$request = $this->getRequest();
		$form = new Application_Form_Regulations_Paragraph();
		 
		if ($request->isPost())
		{
			$data = $request->getPost();
			if (isset($data['leave']))
			{
				$this->flashMe('cancel_edit', 'SUCCESS');
				$this->_redirectToRoute(array('lang' => $this->view->lang), 'regulation');
			}
			else
			{
				if ($form->isValid($request->getPost()))
				{
					$paragraph = new Application_Model_Regualtions();
					$paragraph->populateFromForm($form->getValues());

					$paragraph->save();
					$this->flashMe('paragraph_saved');
					$this->_redirectToRoute(array(), 'regulation');
				}
			}
		}
		else
		{
			$id = $request->getParam('id', null);
			$paragraph = new Application_Model_Regualtions($id);
			$form->populate($paragraph->toArray());
		}
		$this->view->form = $form;
	}

	public function deleteAction()
	{
		$request = $this->getRequest();
		$id = $request->getParam('id', null);
		if ($id)
		{
			$i = 1;
			foreach($this->_getRegulations() as $paragraph)
			{
				if ($paragraph->paragraph_id == $id)
				$paragraph->delete();
				else
				{
					$paragraph->paragraph_no = $i;
					$paragraph->save();
					$i++;
				}
			}
			$this->flashMe('paragraph_deleted');
			$this->_redirectToRoute(array(), 'regulation');
		}
	}

	public function sortAction()
	{
		$request = $this->getRequest();
		$moveId = $request->getParam('move_id', null);
		$paragraphPosition = $request->getParam('position', 1);

		$position = 1;
		foreach ($this->_getRegulations() as $id => $paragraph)
		{
			if ($paragraph->paragraph_id != $moveId && $position < $paragraphPosition)
			{
				$paragraph->paragraph_no = $position ;
				$paragraph->save();
			}
			elseif ($paragraph->paragraph_id != $moveId && $position  > $paragraphPosition)
			{
				$paragraph->paragraph_no = $position + 1;
				$paragraph->save();
			}
			elseif ($paragraph->paragraph_id != $moveId && $position  == $paragraphPosition)
			{
				$paragraph->paragraph_no = $position + 1;
				$paragraph->save();
			}
			elseif ($paragraph->paragraph_id == $moveId)
			{
				$paragraph->paragraph_no = $paragraphPosition;
				$paragraph->save();
				$position--;	//reduce position so next paragraphs would fill the place
			}
			$position++;
		}
		 
		$this->flashMe('regulation_sorted');
		$this->_redirectToRoute(array(), 'regulation');
		 
		 
	}
}

