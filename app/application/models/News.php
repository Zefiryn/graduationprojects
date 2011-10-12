<?php

class Application_Model_News extends GP_Application_Model
{
	public $news_id;
	public $added;
	protected $details;
	protected $files;
	
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
	
	public function getDetail($property, $lang)
	{
		if ($this->details == null)
			$this->__get('details');
		
		foreach($this->details as $text)
		{
			if ($text->lang->lang_code == $lang)
				return $text->$property;
		}
		
		//in case there is no text in given language
		if (is_array($this->details))
			return  $this->details[0]->$property;
		else
			return NULL;

	}
}
