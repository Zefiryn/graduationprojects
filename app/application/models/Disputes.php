<?php

class Application_Model_Disputes extends GP_Application_Model
{
	
	public $dispute_id;
	public $application_id;
	public $user_id;
	protected $user;
	protected $application;

	protected $_dbTableModelName = 'Application_Model_DbTable_Disputes';
	
		public function __construct($id = null, array $options = null)
	{
		return parent::__construct($id, $options);
	}

	

}

