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
		$request = $this->getRequest();
		$page = $request->getParam('page', 1);
		
		if ($this->view->user->role == 'admin')
			$this->view->news_list = $news->getAll(array($page, false));
		else
			$this->view->news_list = $news->getAll(array($page, true));

		$this->view->pages = $news->getPagination();

		$this->view->current_page = $request->getParam('page', 1);
		$this->view->start_pagination = 1;
		$this->view->end_pagination = $news->getPagination();

		$this->render('news/index', null, true);

	}


}

