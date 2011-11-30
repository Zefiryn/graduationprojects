<?php

class Application_Model_NewsDetails extends Zefir_Application_Model
{
	public $news_detail_id;
	public $news_id;
	public $news_text;
	public $news_title;
	public $news_lead;
	public $lang_id;
	protected $news;
	protected $lang;

	protected $_dbTableModelName = 'Application_Model_DbTable_NewsDetails';

	public function isEmpty()
	{
		if ($this->news_text == '' && $this->news_lead == '' && $this->news_title == '')
		return TRUE;
		else
		return FALSE;
	}
}

