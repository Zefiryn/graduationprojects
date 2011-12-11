<?php
class Application_Model_Stages extends GP_Application_Model
{
	public $stage_id;
	public $stage_name;
	public $stage_max_vote;
	public $qualification_score;
	public $order;
	public $active;
	public $translate;
	protected $votes;
	
	protected $_dbTableModelName = 'Application_Model_DbTable_Stages';
	
	public function __construct($id = null, array $options = null)
	{
		return parent::__construct($id, $options);
	}
	
	public function getPreviousStageId()
	{
		$previous = new $this;
		$previous->getBy('order', ($this->order - 1));
		return $previous->stage_id;
	}
	
	public function block()
	{
		$this->active = 0;
		$this->save();
		return $this;
	}
	
	public function activate()
	{
		$this->active = 1;
		$this->save();
		return $this;
	}
	
	public function getMaxScore()
	{
		$jurors = new Application_Model_Jurors();
		$score = 0;
				
		foreach($jurors->fetchAll() as $juror)
		{
			$score += $this->stage_max_vote * $juror->wage;
		}
		
		return $score;
	}
	
	public function countMaxQualificationScore()
	{
		$prev = new $this($this->getPreviousStageId());
		return $prev->getMaxScore();
	}
}