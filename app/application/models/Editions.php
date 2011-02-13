<?php

class Application_Model_Editions extends GP_Application_Model
{
	protected $_edition_id;
	protected $_edition_name;
	
	protected $_dbTableModelName = 'Application_Model_DbTable_Editions';
	
	protected $_set_vars = array('_edition_id', '_edition_name');
	protected $_get_vars = array('_edition_id', '_edition_name');
	
	
	public function __construct(array $options = null) 
	{
	    parent::__construct($options);
	}

}

