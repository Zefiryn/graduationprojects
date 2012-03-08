<?php

class IndexController extends Zefir_Controller_Action
{

	public function init()
	{
		parent::init();
		$this->view->css = array(
				'simple/news.css'
		);
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
		$this->view->end_pagination = $news->getPagination($this->view->user->role);

		$this->view->path = array(
								0 => array('route' => 'news', 'data' => array(), 'name' => array('news_link')),
								1 => array('route' => 'news_page', 'data' => array('page' => $page), 'name' => array('news_page', $page )),
							);
		$this->render('news/index', null, true);

	}


}

