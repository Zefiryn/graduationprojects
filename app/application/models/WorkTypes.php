<?php

class Application_Model_WorkTypes extends GP_Application_Model
{
	protected $_work_type_id;
	protected $_work_type_name;
	
	protected $_dbTableModelName = 'Application_Model_DbTable_WorkTypes';
	
	protected $_set_vars = array('_work_type_id', '_work_type_name');
	protected $_get_vars = array('_work_type_id', '_work_type_name');
	
	
	public function __construct(array $options = null) 
	{
	    parent::__construct($options);
	}
	

}

