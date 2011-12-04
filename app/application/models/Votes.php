<?php
class Application_Model_Votes extends GP_Application_Model
{
	public $vote_id;
	public $stage_id;
	public $juror_id;
	public $application_id;
	protected $juror;
	protected $stage;
	protected $application;
	
	protected $_dbTableModelName = 'Application_Model_DbTable_Votes';
	
	public function __construct($id = null, array $options = null)
	{
		return parent::__construct($id, $options);
	}
}