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
    		2 => array('route' => 'show_news', 'data' => array($news->news_id), 'name' => $news->getDetail('news_title', $this->view->lang)),
    	);
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
    			$form = new Application_Form_Faq();
				if ($form->isValid($request->getPost()))
    			{
    				
    				$this->flashMe('news_saved');
		    		$this->_redirect('/news');	
    			}
    		}
    	}
    	else 
    	{
    		$form = new Application_Form_News();
    	}
    	
    	$this->view->form = $form;
    
    }
    
    
	public function deleteAction()
    {
    	
    }
    
 	


}

