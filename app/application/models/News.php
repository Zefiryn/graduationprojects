<?php

class Application_Model_News extends GP_Application_Model
{
	public $news_id;
	public $news_title;
	public $news_text;
	public $added;
	public $lang_id;
	protected $lang;
	
	protected $_dbTableModelName = 'Application_Model_DbTable_News';
	
	
	public function __construct($id = null, array $options = null) 
	{
	    return parent::__construct($id, $options);
	}
	
	public function getPagination()
	{
		$tplSettings = Zend_Registry::get('tplSettings');
		$rows = $this->getDbTable()->getRowsNum();
		$pages = (int)ceil($rows/$tplSettings->news_limit);
		
		return $pages;
	}
	

}

