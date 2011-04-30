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
	public $work_type_id;
	public $work_desc;
	public $supervisor;
	public $supervisor_degree;
	public $graduation_time;	
	public $application_date;
	public $miniature;
	public $active;
	protected $edition;
	protected $user;
	protected $school;
	protected $degree;
	protected $work_type;
	protected $files;
	
	protected $_update = FALSE;
	
	protected $_dbTableModelName = 'Application_Model_DbTable_Applications';
	
	public function __construct($id = null, array $options = null) 
	{
	    return parent::__construct($id, $options);
	}
	
	public function populateFromForm($data)
	{
		$appSettings = Zend_Registry::get('appSettings');
		parent::populateFromForm($data);
		
		if (isset($data['new_school']) && $data['new_school'] != null)
		{
			$this->school = new Application_Model_Schools();
			$this->school->populate(array('school_name' => $data['new_school']));
		}
		else
			$this->school = new Application_Model_Schools($this->school);
		
		if ($this->application_date == null)
			$this->application_date = time();
		
		if ($this->graduation_time != null)
		{
			$this->graduation_time = strtotime($this->graduation_time);
		}
		
		if ($this->active == null)
			$this->active = 1;
		
		$this->miniature = $data['miniatureCache'];
		
		for($i = 1; $i <= $appSettings->_max_files; $i++)
		{//add uploaded files
			if ($data['file_'.$i]['file_'.$i.'Cache'] != null)
			{
				$this->files[$i]['file_id'] = $data['file_'.$i]['file_id'];
				$this->files[$i]['application_id'] = $data['file_'.$i]['application_id'];
				$this->files[$i]['file'] = $data['file_'.$i]['file_'.$i.'Cache'];
				$this->files[$i]['description'] = $data['file_'.$i]['file_annotation'];
			}  
		}
	}
	
	public function delete()
	{
		return $this->getDbTable()->delete($this);
	}
	
	public function getApplications($edition, $sort = NULL)
	{
		$sort = $sort != NULL ? array($sort,'application_date ASC') : 'application_date ASC';
		
		$rowset = $this->getDbTable()->getAllApplications($sort);
		
		$applications = array();
		foreach($rowset as $row)
		{
			$application = new $this;
			$applications[] = $application->populate($row, $application);
		}
		
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
		$data = array(
				'country' => $this->country,
				'application_id' => $this->application_id,
				'edition' => $this->edition_id,
				'user' => $this->user_id,
				'school' => $this->school_id,
				'department' => $this->department,
				'degree' => $this->degree_id,
				'work_subject' => $this->work_subject,
				'work_type' => $this->work_type_id,
				'work_desc' => $this->work_desc,
				'supervisor_degree' => $this->supervisor_degree,
				'supervisor' => $this->supervisor,
				'graduation_time' => date('d-m-Y', $this->graduation_time),
				'miniatureCache' => 'miniatures/'.$this->miniature,
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
			$data['file_'.$i]['file_annotation'] = $file->file_desc;
		}
		
		return $data;
	}

}

