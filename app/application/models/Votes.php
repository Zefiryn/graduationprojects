<?php
class Application_Model_Votes extends GP_Application_Model
{
	public $vote_id;
	public $stage_id;
	public $juror_id;
	public $application_id;
	public $vote;
	protected $juror;
	protected $stage;
	protected $application;
	
	protected $_dbTableModelName = 'Application_Model_DbTable_Votes';
	
	public function __construct($id = null, array $options = null)
	{
		return parent::__construct($id, $options);
	}
	
	public function getVotesByJurors($stage_id)
	{
		$rowset = $this->getDbTable()->fetchAll('stage_id = '.$stage_id);
		
		$votes = array();
		foreach($rowset as $row)
		{
			$votes[$row['application_id']][$row['juror_id']] = $row['vote'];
		}
		
		return $votes;
	}
}