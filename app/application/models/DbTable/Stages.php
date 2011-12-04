<?php

class Application_Model_DbTable_Stages extends Zefir_Application_Model_DbTable
{
	protected $_raw_name = 'stages';
	
	protected $_primary = 'stage_id';
	
	protected $_hasMany = array(
	
       	'wages' => array(
       		'model' => 'Application_Model_Wages',
			'refColumn' => 'stage_id',
		),
	);
	
	public function __construct($config = array())
	{
		parent::__construct($config);
	}
}