<?php

class Application_Model_DiplomaFiles extends GP_Application_Model
{
	public $file_id;
	public $path;
	public $file_desc;
	public $diploma_id;
	protected $diploma;
	protected $_image = array(
		'property' => 'path',
		'dir' => '/assets/editions'
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
	
	protected $_dbTableModelName = 'Application_Model_DbTable_DiplomaFiles';
	
	public function __construct($id = null, array $options = null) 
	{
	    return parent::__construct($id, $options);
	}
	
	public function getFileFolder()
	{
		return '/'.substr($this->path, 0, strrpos($this->path, '/'));
	} 
	
	public function getFileName()
	{
		return substr($this->path, strrpos($this->path, '/')+1);
	}
	
	public function recreateThumbnails()
	{
		$options = Zend_Registry::get('options');
		foreach($this->getThumbnails() as $key)
		{
			$dir = APPLICATION_PATH.'/../public'.$options['upload']['diplomas'].'/';
			$this->getDbTable()->rerunResize($this, 'path', $dir, $key);
		}
	}
	
}

