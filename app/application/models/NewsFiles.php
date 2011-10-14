<?php

class Application_Model_NewsFiles extends Zefir_Application_Model
{
	public $news_file_id;
	public $news_id;
	public $path;
	public $main_image;
	protected $news;

	protected $_dbTableModelName = 'Application_Model_DbTable_NewsFiles';
}
