<?php

class DiplomasController extends Zefir_Controller_Action
{

	public function init()
	{
		parent::init();
	}

	public function indexAction()
	{
		$edition = new Application_Model_Editions();
		$editions = $edition->getEditions('DESC');
		$selected_edition = $this->getRequest()->getParam('edition', array_shift($editions));
		$selected_edition = str_replace('-', '–', $selected_edition);

		$edition->getEdition($selected_edition, TRUE);

		$this->view->diplomas = $edition->diplomas;
		$this->view->path = array(
							0 => array('route' => 'root', 'data' => array(), 'name' => array('main_page')),
							1 => array('route' => 'diplomas', 'data' => array('edition' => $selected_edition), 'name' => array('edition', $selected_edition)),
							);
	}

	public function showAction()
	{
		$request = $this->getRequest();
		$id = $request->getParam('id', null);
		$diploma = new Application_Model_Diplomas($id);
		$this->view->diploma = $diploma;
		$this->view->adjacent = $diploma->getAdjacentDiplomas();

		$this->view->path = array(
		0 => array('route' => 'root', 'data' => array(), 'name' => array('main_page')),
		1 => array('route' => 'diplomas', 'data' => array('edition' => $diploma->edition->edition_name), 'name' => array('edition', $diploma->edition->edition_name)),
		2 => array('route' => 'show_diploma', 'data' => array('id' => $diploma->diploma_id), 'name' => array($diploma->getAuthorName())),
		);
	}


	public function newAction()
	{

	}

	public function editAction()
	{
		$request = $this->getRequest();
		$form = new Application_Form_Diploma();
		$id = $request->getParam('id', null);
		$diploma = new Application_Model_Diplomas($id);
		 
		if ($request->isPost())
		{
			if ($request->getParam('leave'))
			{
				$this->flashMe('cancel_edit');
				$this->_redirectToRoute(array('id' => $diploma->diploma_id), 'show_diploma');
			}
			if ($form->isValid($request->getPost()))
			{
				$diploma->populateFieldsFromForm($form->getValues());
				$diploma->save();
				$this->flashMe('diploma_saved');
				$this->_redirectToRoute(array('id' => $diploma->diploma_id), 'show_diploma');
			}

		}
		else
		{
			$form->populate($diploma->prepareFormArray());
		}
		 
		$this->view->form = $form;
	}

	public function deleteAction()
	{
		 
	}

	public function sortAction()
	{
		$request = $this->getRequest();
		$id = $request->getParam('id', null);
		$file_id = $request->getParam('file_id', null);
		$new_position = $request->getParam('position', 1);
		$diploma = new Application_Model_Diplomas($id);
		 
		$position = 1;
		foreach ($diploma->files as $file)
		{
			if ($file->file_id != $file_id && $position < $new_position)
			{
				$file->position = $position ;
				$file->save();
			}
			elseif ($file->file_id != $file_id && $position  > $new_position)
			{
				$file->position = $position + 1;
				$file->save();
			}
			elseif ($file->file_id != $file_id && $position  == $new_position)
			{
				$file->position = $position + 1;
				$file->save();
			}
			elseif ($file->file_id == $file_id)
			{
				$file->position = $new_position;
				$file->save();
				$position--;	//reduce position so next paragraphs would fill the place
			}
			$position++;
		}
		 
		//$this->_helper->viewRenderer->setNoRender(true);
	}


}

