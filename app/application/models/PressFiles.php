<?php

class Application_Model_PressFiles extends GP_Application_Model
{
	public $press_file_id;
	public $element_id;
	public $path;
	protected $press;
	protected $_uploadDir = 'press';
	protected $_image = array(
			'property' => 'path',
			'dir' => '/assets/press'
	);
	protected $_imageData = array();
	
	protected $_dbTableModelName = 'Application_Model_DbTable_PressFiles';

	public function __construct($id = null, array $options = null)
	{
		return parent::__construct($id, $options);
	}

	public function getUploadDir()
	{
		return APPLICATION_PATH . '/../public/assets/' . $this->_uploadDir;
	}
	
	public function getPath()
	{
		$options = Zend_Registry::get('options');
		$baseUrl = isset($options['resources']['frontController']['baseUrl']) ?  $options['resources']['frontController']['baseUrl'] : '/';
		$baseUrl .= Zefir_Filter::getLastChar($baseUrl) != '/' ? '/' : null;  
		return $baseUrl . 'assets/' . $this->_uploadDir .'/' . $this->path;
	}
}

