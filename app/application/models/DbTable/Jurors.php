<?php

class Application_Model_DbTable_Jurors extends Zefir_Application_Model_DbTable
{
	protected $_raw_name = 'jurors';
	
	protected $_primary = 'juror_id';
	
	protected $_hasMany = array(
	
       	'users' => array(
       		'model' => 'Application_Model_Users',
			'refColumn' => 'juror_id',
		),
		'votes' => array(
	       		'model' => 'Application_Model_Votes',
				'refColumn' => 'juror_id',
		),
	);
	
	public function __construct($config = array())
	{
		parent::__construct($config);
	}
}