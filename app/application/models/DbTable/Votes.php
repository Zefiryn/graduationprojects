<?php

class Application_Model_DbTable_Votes extends Zefir_Application_Model_DbTable
{
	protected $_raw_name = 'votes';
	
	protected $_primary = 'vote_id';
	
	protected $_belongsTo = array(
	
		'juror' => array(
       		'model' => 'Application_Model_Jurors',
				'column' => 'juror_id',
				'refColumn' => 'juror_id',
		),
		'stage' => array(
       		'model' => 'Application_Model_Stages',
				'column' => 'stage_id',
				'refColumn' => 'stage_id',
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