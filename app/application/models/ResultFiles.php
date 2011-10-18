<?php

class Application_Model_ResultFiles extends GP_Application_Model
{
	public $file_id;
	public $path;
	public $file_desc;
	protected $result;
	
	protected $_dbTableModelName = 'Application_Model_DbTable_ResultFiles';
	
	public function __construct($id = null, array $options = null) 
	{
	    return parent::__construct($id, $options);
	}
	
	
}

