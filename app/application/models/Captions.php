<?php

class Application_Model_Captions extends GP_Application_Model
{
	public $caption_id;
	public $name;
	protected $localizations;
	
	protected $_dbTableModelName = 'Application_Model_DbTable_Captions';
	
	public function __construct($id = null, array $options = null) 
	{
	    return parent::__construct($id, $options);
	}
}

