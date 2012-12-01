<?php
class Application_Model_Jurors extends GP_Application_Model
{
  public $juror_id;
  public $juror_name;
  public $country;
  public $wage;
  protected $users;
  protected $votes;
  protected $_cachedVotes = array();
  
  protected $_dbTableModelName = 'Application_Model_DbTable_Jurors';
  
  public function __construct($id = null, array $options = null)
  {
    return parent::__construct($id, $options);
  }
  
  public function getJurorVotes($stage)
  {
    $this->__get('votes');
    foreach($this->votes as $vote)
    {
      $this->_cachedVotes[$vote->stage_id][$vote->application_id] = $vote->vote;
    }
    
    return isset($this->_cachedVotes[$stage]) ? $this->_cachedVotes[$stage] : array();
  }
  
  public function getJurorVote($stage, $application)
  {
    if (!isset($this->_cachedVotes[$stage]))
    {
      $this->getJurorVotes($stage);
    }
    
    if (isset($this->_cachedVotes[$stage]) && isset($this->_cachedVotes[$stage][$application]))
    {
      return $this->_cachedVotes[$stage][$application];
    }
    else
    {
      return null;
    }
  }
  
  public function prepareFormArray() 
  {
    $users = array();
    foreach($this->__get('users') as $user)
    {
      $users[] = $user->user_id;
    }
    
    return array('juror_id' => $this->juror_id, 'users' => $users);
  }
  
}