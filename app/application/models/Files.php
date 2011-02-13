<?php

class Application_Model_Files extends GP_Application_Model
{
	protected $_file_id;
	protected $_application;
	protected $_path;
	protected $_file_desc;
	
	protected $_dbTableModelName = 'Application_Model_DbTable_Files';
	
	protected $_set_vars = array('_file_id', '_application', '_path', '_file_desc');
	protected $_get_vars = array('_file_id', '_application', '_path', '_file_desc');
	
	
	public function __construct(array $options = null) 
	{
	    parent::__construct($options);
	}
	

}

