<?php

class Application_Model_DbTable_Stages extends Zefir_Application_Model_DbTable
{
	protected $_raw_name = 'stages';
	
	protected $_primary = 'stage_id';
	
	protected $_hasMany = array(
	
       	'votes' => array(
       		'model' => 'Application_Model_Votes',
			'refColumn' => 'stage_id',
		),
	);
	
	public function __construct($config = array())
	{
		parent::__construct($config);
	}
	
	public function getFinalStage()
	{
		$select = $this->select()->where('final = 1')->limit(1);
		return $this->fetchRow($select);
	}
}