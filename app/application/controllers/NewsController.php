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
		$this->view->news_list = $news->fetchAll(1);
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
    	$this->view->path = array(
    		0 => array('route' => 'root', 'data' => array(), 'name' => 'main_page'),
    		1 => array('route' => 'news', 'data' => array(), 'name' => 'news_link'),
    		2 => array('route' => 'show_news', 'data' => array('id' => $news->news_id), 'name' => $news->getDetail('news_title', $this->view->lang)),
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
    			$this->_redirect('/news');
    		}
    		else 
    		{
    			
    		}
    		
    	}
    	
    	$this->view->form = $form;
    	
    }
    
    public function editAction()
    {
    	$request = $this->getRequest();
    
    	if ($request->isPost())
    	{
    		$data = $request->getPost();
    		if (isset($data['leave']))
    		{
    			$this->flashMe('cancel_edit', 'SUCCESS');
    			$this->_redirect('/news');
    		}
    		else 
    		{
    			$form = new Application_Form_News();
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
    		$form = new Application_Form_News();
    		$news = new Application_Model_News($request->getParam('id', null));
    		$form->populate($news->prepareFormArray($this->view->lang));
    	}
    	
    	$this->view->form = $form;
    
    }
    
    
	public function deleteAction()
    {
    	
    }
    
 	


}

