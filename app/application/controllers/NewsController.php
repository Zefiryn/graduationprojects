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
		if ($this->view->user->role == 'admin')
			$this->view->news_list = $news->fetchAll(array(1, false));
		else 
			$this->view->news_list = $news->fetchAll(array(1, true));
		
		$this->view->pages = $news->getPagination();
		
		$this->view->current_page = 1;
		$this->view->start_pagination = 1;
		$this->view->end_pagination = 1;
		
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
					//$this->flashMe('news_saved');
					//$this->_redirectToRoute(array('id' => $news->news_id), 'show_news');
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
    	
    	$this->view->form = $form;
    }
    
    
	public function deleteAction()
    {
    	$request = $this->getRequest();
		$news = new Application_Model_News($request->getParam('id'));
		$news->delete();
		$this->flashMe('news_deleted');
		$this->_redirectToRoute(array(), 'news');
    	
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
    
 	


}

