<?php
class Application_Model_Stages extends GP_Application_Model
{
	public $stage_id;
	public $stage_name;
	public $stage_max_vote;
	public $active;
	
	protected $_dbTableModelName = 'Application_Model_DbTable_Stages';
	
	public function __construct($id = null, array $options = null)
	{
		return parent::__construct($id, $options);
	}
}