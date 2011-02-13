<?php

class Application_Model_Schools extends GP_Application_Model
{
	protected $_school_id;
	protected $_school_name;
	
	protected $_dbTableModelName = 'Application_Model_DbTable_Schools';
	
	protected $_set_vars = array('_school_id', '_school_name');
	protected $_get_vars = array('_school_id', '_school_name');
	
	
	public function __construct(array $options = null) 
	{
	    parent::__construct($options);
	}
	

}

