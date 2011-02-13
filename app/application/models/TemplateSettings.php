<?php

class Application_Model_TemplateSettings extends GP_Application_Model
{ 
	protected $_template_name; 
	protected $_template_id;
	
	protected $_dbTableModelName = 'Application_Model_DbTable_TemplateSettings';
	
	protected $_set_vars = array('_template_name', '_template_id');
	protected $_get_vars = array('_template_name', '_template_id');
	
	public function __construct(array $options = null) 
	{
	    parent::__construct($options);
	}
}

