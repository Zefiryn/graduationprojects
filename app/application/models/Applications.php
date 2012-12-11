<?php

class Application_Model_Applications extends GP_Application_Model
{
  public $application_id;
  public $edition_id;
  public $user_id;
  public $country;
  public $school_id;
  public $department;
  public $degree_id;
  public $work_subject;
  public $work_site;
  public $work_type_id;
  public $work_desc;
  public $supervisor;
  public $supervisor_degree;
  public $graduation_time;
  public $application_date;
  public $active;
  protected $edition;
  protected $user;
  protected $school;
  protected $degree;
  protected $work_type;
  protected $files;
  protected $votes;
  protected $disputes;
  protected static $_jurorCount;

  protected $_update = FALSE;

  protected $_dbTableModelName = 'Application_Model_DbTable_Applications';
  
  protected $_miniature = array('name' => 'miniature','files' => 1);

  public function __construct($id = null, array $options = null)
  {
    return parent::__construct($id, $options);
  }

  public function populateFromForm($data)
  {
    $appSettings = Zend_Registry::get('appSettings');
    parent::populateFromForm($data);

    $this->school = new Application_Model_Schools();
    $this->school->populateFromForm(array('school_name' => $data['school']));

    if ($this->application_date == null)
    $this->application_date = time();

    if ($this->graduation_time != null)
    {
      $this->graduation_time = strtotime($this->graduation_time);
    }

    if ($this->active == null)
    $this->active = 1;

    for($i = 1; $i <= $appSettings->max_files; $i++)
    {
      //add uploaded files
        
      if ($data['file_'.$i]['file_'.$i.'Cache'] != null)
      {
        $this->files[$i]['file_id'] = $data['file_'.$i]['file_id'];
        $this->files[$i]['application_id'] = $data['file_'.$i]['application_id'];
        $this->files[$i]['file'] = $data['file_'.$i]['file_'.$i.'Cache'];
      }
    }

    if (isset($data['user_id']))
    {
      $this->user = new Application_Model_Users($data['user_id']);
    }
  }

  public function delete()
  {
    return $this->getDbTable()->delete($this);
  }

  public function getApplications($sort = NULL, $stage = NULL, $filter = null, $range = array(), $country = null, $user = null)
  {
    if (!$user) $user = Zend_Registry::get('user');
    
    $sort = strstr($sort, 'work_type_id') ? 'a.'.$sort : $sort;
    
    $sort = $sort != NULL ? array($sort, 'surname ASC', 'name ASC', 'application_date ASC') : array('application_date ASC', 'surname ASC');
    
    $rowset = $this->getDbTable()->getAllApplications($sort, $stage);

    $applications = array();
    foreach($rowset as $row)
    {
      $application = new $this;
      $application->populate($row);
      if ($stage->isFinalStage()) {
        $applications[$application->__get('work_type')->work_type_name][$row['country']][$row['application_id']] = $application;
      }
      elseif (!$filter || $filter == 'all') 
      {
        $applications[$row['application_id']] = $application;
      }
      elseif ($filter == 'unmarked')
      {
        
        if ($application->countScore($stage->stage_id, $user) === null)
        {
          $applications[$row['application_id']] = $application;
        }
      }
      elseif ($filter == 'disputed')
      {
        if ($application->isDisputed())
        {
          $applications[$row['application_id']] = $application;
        }
      }
      elseif($filter == 'qualified')
      {
        if ($application->inStage($stage->getNextStage()))
        {
          $applications[$row['application_id']] = $application;
        }
      }
      elseif($filter == 'notqualified')
      {
        if (!$application->inStage($stage->getNextStage()))
        {
          $applications[$row['application_id']] = $application;
        }
      }
      elseif ($filter == 'range' && count($range) == 2)
      {
        $score = $application->countScore($stage->stage_id, $user);
        
        $start = $range['start'] == null ? -1 : $range['start'];
        $end = $range['end'] == null ? 999 : $range['end']; 
        
        if ($score >= $start && $score <= $end)
        {
          $applications[$row['application_id']] = $application;
        }
      }
      elseif ($filter == 'country') {
        
        if ($application->country == $country)
        {
          $applications[$row['application_id']] = $application;
        }
      }
      
    }
    
    $sortedApps = new Zend_Session_Namespace('sortedApps');
    $sortedApps->keys = array_keys($applications);
    
    return $applications;
  }


  public function getSupervisor()
  {
    return $this->supervisor_degree.' '.$this->supervisor;
  }

  public function getApplicationSchool()
  {
    return $this->school->school_name.', '.$this->department;
  }

  public function prepareFormArray()
  {
    $this->__get('school');
    $data = array(
        'country' => $this->country,
        'application_id' => $this->application_id,
        'edition_id' => $this->edition_id,
        'user_id' => $this->user_id,
        'school' => $this->school->school_name,
        'department' => $this->department,
        'degree_id' => $this->degree_id,
        'work_site' => $this->work_site,
        'work_subject' => $this->work_subject,
        'work_type_id' => $this->work_type_id,
        'work_desc' => $this->work_desc,
        'supervisor_degree' => $this->supervisor_degree,
        'supervisor' => $this->supervisor,
        'graduation_time' => date('d-m-Y', $this->graduation_time),
        'personal_data_agreement' => TRUE
    );

    if ($this->files == null)
    $this->__get('files');
      
    foreach($this->files as $no => $file)
    {
      $i = ++$no;
      $data['file_'.$i]['application_id'] = $this->application_id;
      $data['file_'.$i]['file_id'] = $file->file_id;
      $data['file_'.$i]['file_'.$i.'Cache'] = 'applications/'.$file->path;
    }

    return $data;
  }
  
  public function prepareArchiveArray()
  {
    $data = $this->prepareFormArray();
    $data['graduation_time'] = $this->graduation_time;
    $data['name'] = $this->__get('user')->name;
    $data['surname'] = $this->__get('user')->surname;
    $data['email'] = $this->__get('user')->email;
    $data['show_email'] = $this->__get('user')->show_email;
    
    return $data;
  }
  
  public function getAdjacentApplication()
  {
    return $this->getDbTable()->getAdjacentApplication($this);
  }
  
  public function createMiniature()
  {
    for ($i = 0; $i < $this->_miniature['files']; $i++) 
    {
      $file = $this->files[$i];
      $path = $file->getImage('miniature');
      if (!file_exists(APPLICATION_PATH.'/../public/assets/applications/'.$path)) 
      {
        $file->getDbTable()->rerunResize($file, 'path', APPLICATION_PATH.'/../public/assets/applications/', 'miniature'); 
      } 
    }
    
  }
  
  public function getMiniature($index = 1) 
  {
    $this->__get('files');
    
    if (count($this->files) > 0) {
      $file = $this->files[$index - 1];
      $path = $file->getImage('miniature');
      
      if (!file_exists(APPLICATION_PATH.'/../public/assets/applications/'.$path))
      {
        $this->createMiniature();
      }
      
      return $path;
    }
  }
  
  public function getVotes($stage)
  {
    if ($this->votes === null)
    {
      $this->__get('votes');
    }
    
    $votes = array();
    foreach($this->votes as $vote)
    {
      if ($vote->stage_id = $stage)
      {
        $votes[$vote->vote][] = $vote;
      }
    }
    
    return $votes;    
  }
  
  public function getVotesByJurors($stage)
  {
    if ($this->votes === null)
    {
      $this->__get('votes');
    }
  
    $votes = array();
    
    foreach($this->votes as $vote)
    {
      if ($vote->stage_id == $stage)
      {
        $votes[$vote->juror_id]= array('vote' => $vote->vote,
                                      'wage' => $vote->juror->wage,
                                      'juror_name' => $vote->juror->juror_name);
      }
      
    }
  
    return $votes;
  }
  
  public function getJurorVote($stage_id, $juror_id)
  {
    if ($this->votes == null)
    {
      $this->__get('votes');
    }
    
    foreach($this->votes as $vote)
    {
      if ($vote->stage_id == $stage_id && $vote->juror_id == $juror_id)
      {
        return $vote->vote;
      }
    }
    return null;
  }
  
  public function countScore($stage, $user = null)
  {
    if ($this->votes == null)
    {
      $this->__get('votes');
    }
    $score = null;
    foreach($this->votes as $vote)
    {
      if ($vote->stage_id == $stage)
      {
        if (!$user || $user->_role == 'admin' || $user->juror_id == $vote->juror_id)
        {
          $score += $vote->vote;
        }
      }
    }
    return $score;
  }
  
  public function inStage($stage)
  {
    if ($stage->order > 1)
    {
      $votes = $this->getDbTable()->countVotes($stage->getPreviousStageId(), $this->application_id);
      if ($votes >= $stage->qualification_score) 
      {
        return TRUE;
      }
      else
      {
        return FALSE;
      }
    }
    else
    {
      return TRUE;
    }
  }
  
  public function hasFiles() {
    return count($this->__get('files')) > 0;
  }
  
  public function isDisputed($user = null)
  {
    if ($this->disputes == null)
    {
      $this->__get('disputes');
    }
    
    if (!is_array($this->disputes)) $this->disputes = array();
    
    foreach($this->disputes as $dispute)
    {
      if ($user == null || $dispute->user_id == $user->user_id)
      {
        return TRUE;
      }
    }
    
    return FALSE;
  }
  
  public function getDispute($user)
  {
    if ($this->disputes === null)
    {
      $this->__get('disputes');
    }
    foreach($this->disputes as $dispute)
    {
      if ($dispute->user_id == $user->user_id)
      {
        return $dispute;
      }
    }
  
    return FALSE;
  }
  
  public function getWinningApps($edition_id)
  {
    $stage = new Application_Model_Stages();
    $stage->getFinalStage();
    
    return $this->getApplications('surname' , $stage, null, null, null);
  }
  
  public function getRemainedApps($apps_keys)
  {
    foreach($this->getDbTable()->getRemainedApps($apps_keys) as $app)
    {
      $obj = new $this;
      $set[$app->application_id] = $obj->populate($app);
    }
    
    return $set;
  }
  
  public function archive($app_ids)
  {
    $ids = explode(',', $app_ids);
    $errors  =array('hasError' => false);
    foreach($ids as $id)
    {
      $app = new $this($id);
      $diploma = new Application_Model_Diplomas();
      try 
      {
        $diploma->createFromApp($app);
        $errors[$id]['object'] = $diploma;
      }
      catch (Zend_Exception $e)
      {
        $errors['hasError'] = true;
        $errors[$id]['error'] = $e->getMessage();
      }
    }
    
    return $errors;
  }
  
  public function getField($name)
  {
    if (is_object($this->__get($name))) 
    {
      return $this->$name->__toString();
    }
    else
    {
      return $this->$name;
    }
  }

  public function setWinner()
  {
    $jurors = new Application_Model_Jurors();
    $stages = new Application_Model_Stages();
    $stage = $stages->getFinalStage();
    $max_vote = $stage->stage_max_vote;
    foreach($jurors->fetchAll() as $juror) {
      $score = $juror->wage * $max_vote;
      $vote = new Application_Model_Votes();
      $vote->stage_id = $stage->stage_id;
      $vote->juror_id = $juror->juror_id;
      $vote->application_id = $this->application_id;
      $vote->vote = $score;
      $vote->save();
      //echo '<br /><br />';
    }
  }
}