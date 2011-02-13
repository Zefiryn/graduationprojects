<?php

class Application_Model_Degrees extends GP_Application_Model
{
	protected $_degree_id;
	protected $_degree_name;
	
	protected $_dbTableModelName = 'Application_Model_DbTable_Degrees';
	
	protected $_set_vars = array('_degree_id', '_degree_name');
	protected $_get_vars = array('_degree_id', '_degree_name');
	
	
	public function __construct(array $options = null) 
	{
	    parent::__construct($options);
	}
	

}

