<?php

class Application_Model_NewsDetails extends Zefir_Application_Model
{
	public $news_detail_id;
	public $news_id;
	public $news_text;
	public $news_lead;
	public $lang_id;
	protected $news;
	protected $lang;
	
	protected $_dbTableModelName = 'Application_Model_DbTable_NewsDetails';

}

