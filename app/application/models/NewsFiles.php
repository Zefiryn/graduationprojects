<?php

class Application_Model_NewsFiles extends Zefir_Application_Model
{
	public $news_file_id;
	public $news_id;
	public $path;
	public $main_image;
	protected $news;
	protected $_image = array(
		'property' => 'path',
		'dir' => '/assets/images'
	);
	protected $_imageData = array(
		'thumb' => array(
			'width' => 220,
			'height' => 210,
			'crop' => false,
			'ratio' => 'width' //save ratio according to new width
		),
		'small' => array(
			'width' => 470,
			'height' => 260,
			'crop' => false,
			'ratio' => 'width'	//save ratio according to new width
		)
	);

	protected $_dbTableModelName = 'Application_Model_DbTable_NewsFiles';
	
	
	
	public function getFileFolder()
	{
		if (strstr($this->path, 'assets'))
			$this->path = substr($this->path, strrpos($this->path, '/')+1);
			
		return $this->_image['dir'];
	} 
	
}
