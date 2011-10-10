<?php

class IndexController extends Zefir_Controller_Action
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


}

