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
  public $final;
  protected $votes;

  static protected $_jurors;
  
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

  public function getPreviousStage()
  {
    $previous = new $this;
    $previous->getBy('order', ($this->order - 1));
    return $previous;
  }

  public function getNextStage()
  {
    $next = new $this;
    $next->getBy('order', ($this->order + 1));
    return $next;
  }

  public function getNextStageId()
  {
    $previous = new $this;
    $previous->getBy('order', ($this->order + 1));
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
    $score = 0;
        
    foreach($this->_getJurors() as $juror)
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
  
  public function getList()
  {
    $array = array();
    foreach($this->fetchAll() as $stage)
    {
      $array[$stage->stage_id] = $stage;
    }
    
    return $array;
  }
  
  public function getFinalStage()
  {
    $row = $this->getDbTable()->getFinalStage();
    $this->populate($row);
    
    return $this;
  }

  protected function _getJurors() 
  {
    if (self::$_jurors == null) 
    {
      $jurors = new Application_Model_Jurors();
      self::$_jurors = $jurors->fetchAll();
    }

    return self::$_jurors;
  }

  public function isFinalStage() 
  {
    return $this->final == 1;
  }
}