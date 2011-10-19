<?php

class Application_Model_DiplomaFiles extends GP_Application_Model
{
	public $file_id;
	public $path;
	public $file_desc;
	public $diploma_id;
	protected $diploma;
	
	protected $_dbTableModelName = 'Application_Model_DbTable_DiplomaFiles';
	
	public function __construct($id = null, array $options = null) 
	{
	    return parent::__construct($id, $options);
	}
	
	
}

