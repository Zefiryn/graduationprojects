<?php

class NewsController extends Zefir_Controller_Action
{

	public function init()
	{
		parent::init();
	}

	public function indexAction()
	{
		$news = new Application_Model_News();
		$request = $this->getRequest();
		$page = $request->getParam('page', 1);
		
		if ($this->view->user->role == 'admin')
			$this->view->news_list = $news->getAll(array($page, false));
		else
			$this->view->news_list = $news->getAll(array($page, true));

		$this->view->pages = $news->getPagination($this->view->user->role);

		$this->view->current_page = $request->getParam('page', 1);
		$this->view->start_pagination = 1;
		$this->view->end_pagination = $news->getPagination();

	}

	public function showAction()
	{
		$request = $this->getRequest();
		$id = $request->getParam('id', null);
		 
		$news = new Application_Model_News($id);

		$this->view->news = $news;
		 
		$link = $news->getDetail('news_title', $this->view->lang) ? $news->getDetail('news_title', $this->view->lang) : $news->link;
		 
		$this->view->path = array(
		0 => array('route' => 'root', 'data' => array(), 'name' => array('main_page')),
		1 => array('route' => 'show_news', 'data' => array('id' => $news->news_id), 'name' => array($link)),
		);
	}

	public function newAction()
	{
		$form = new Application_Form_News();
		$request = $this->getRequest();
		 
		if ($request->isPost())
		{
			$data = $request->getPost();
			if (isset($data['leave']))
			{
				$this->flashMe('cancel_edit', 'SUCCESS');
				$this->_redirectToRoute(array(), 'news');
			}
			else
			{
				if ($form->isValid($request->getPost()))
				{
					$news = new Application_Model_News();
					$news->populateFromForm($form->getValues());
					$news->added = time();
					$news->save();
					$this->flashMe('news_saved');
					$this->_redirectToRoute(array('id' => $news->news_id), 'show_news');
				}
			}

		}
		 
		$this->view->form = $form;
		$this->view->fileForm = new Application_Form_News_File();

		$this->view->path = array(
		0 => array('route' => 'root', 'data' => array(), 'name' => array('main_page')),
		1 => array('route' => 'new_news', 'data' => array(), 'name' => array('new_news')),
		);
		 
	}

	public function editAction()
	{
		$request = $this->getRequest();
		$form = new Application_Form_News();
		 
		if ($request->isPost())
		{
			$data = $request->getPost();
			if (isset($data['leave']))
			{
				$this->flashMe('cancel_edit', 'SUCCESS');
				$this->_redirectToRoute(array('id' => $request->getParam('id', null)), 'show_news');
			}
			else
			{
				 
				if ($form->isValid($request->getPost()))
				{
					$news = new Application_Model_News($form->getElement('news_id')->getValue());
					$news->populateFromForm($form->getValues());
					$news->save();
					$this->flashMe('news_saved');
					$this->_redirectToRoute(array('id' => $request->getParam('id')), 'show_news');
				}
			}
		}
		else
		{
			$news = new Application_Model_News($request->getParam('id', null));
			$form->populate($news->prepareFormArray());
		}
		 
		$this->view->news = $news;
		$this->view->form = $form;
	}


	public function deleteAction()
	{
		$request = $this->getRequest();
		$news = new Application_Model_News($request->getParam('id'));
		$news->delete();
		$this->flashMe('news_deleted');
		$data = array(0 => $this->view->url(array(), 'news'));
		$this->_helper->json($data);
	}

	public function uploadAction()
	{
		$request = $this->getRequest();
		 
		if ($request->isPost())
		{
			$file = new Application_Form_News_File();
			$options  = Zend_Registry::get('options');
			$file = $this->_cacheFile($options['upload']['cache'], $file, 'file');

			$uploadedFile = $file->file->getFileName();
			if (!is_array($uploadedFile))
			{
				$uploadedFile = substr($uploadedFile, strpos($uploadedFile, '../')+10);
				$files = $request->getParam('news_files');
				$files .= $uploadedFile.', ';
				$file->getElement('news_files')->setValue($files);
			}
			else
			{
				$file->getElement('news_files')->setValue($request->getParam('news_files'));
			}
		}
		else
		{
			$file = new Application_Form_News_File();
		}
		$this->view->fileForm = $file;
		$this->_helper->layout->disableLayout();
		 
	}

	public function deleteImageAction()
	{
		$this->_helper->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
		$request = $this->getRequest();
		if ($request->isXmlHttpRequest())
		{
			$id = $request->getParam('id');
	
			if (ctype_digit($id))
			{
				$file = new Application_Model_NewsFiles($id);
				$file->delete();
				$this->_helper->json(array("file_id" => $id));
				
			}
		}
		else
		{
			$this->flashMe('ajax_only', 'FAILURE');
			$this->_redirectToRoute(array(), 'root');
		}
	}
	
	/*
	 * @deprecated 
	 * 
	 * This action is deprecated as news files has order
	 */
	public function mainImageAction()
	{
		$request = $this->getRequest();
		$this->_helper->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
		if ($request->isXmlHttpRequest())
		{
			$id = $request->getParam('id');
			$file_id = $request->getParam('file_id');
			if (ctype_digit($id))
			{
				$news = new Application_Model_News($id);
				foreach($news->files as $file)
				{
					if ($file->news_file_id == $file_id)
					{
						$file->main_image = 1;
						$file->save();
					}
					elseif ($file->main_image = 1)
					{
						$file->main_image = 0;
						$file->save();
					}
				}
				$this->_helper->json(array("success" => TRUE));
	
			}
		}
		else
		{
			$this->flashMe('ajax_only', 'FAILURE');
			$this->_redirectToRoute(array(), 'news');
		}
	}
	
	public function sortAction()
	{
		$request = $this->getRequest();
		$this->_helper->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
		
		if ($request->isXmlHttpRequest())
		{
			$id = $request->getParam('news_id', null);
			$file_id = $request->getParam('file_id', null);
			$new_position = $request->getParam('position', null);
			
			$news = new Application_Model_News($id);
			
			$position = 1;
			foreach ($news->files as $file)
			{
				if ($file->news_file_id != $file_id && $position < $new_position)
				{
					$file->position = $position ;
					$file->save();
				}
				elseif ($file->news_file_id != $file_id && $position  > $new_position)
				{
					$file->position = $position + 1;
					$file->save();
				}
				elseif ($file->news_file_id != $file_id && $position  == $new_position)
				{
					$file->position = $position + 1;
					$file->save();
				}
				elseif ($file->news_file_id == $file_id)
				{
					$file->position = $new_position;
					$file->save();
					$position--;	//reduce position so next file would fill the place
				}
				$position++;
			}
			
			$this->_helper->json(array('success' => true));
		}
		else
		{
			$this->flashMe('ajax_only', 'FAILURE');
			$this->_redirectToRoute(array(), 'news');
		}
	}


}

