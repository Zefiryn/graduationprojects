<?php

class Application_Model_DbTable_Disputes extends Zefir_Application_Model_DbTable
{
	protected $_raw_name = 'disputes';
	
	protected $_primary = 'dispute_id';
	
	protected $_belongsTo = array(
	
		'user' => array(
       		'model' => 'Application_Model_Users',
				'column' => 'user_id',
				'refColumn' => 'user_id',
		),
		'application' => array(
				'model' => 'Application_Model_Applications',
				'column' => 'application_id',
				'refColumn' => 'application_id',
		)
	);
	
	public function __construct($config = array())
	{
		parent::__construct($config);
	}
}