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
	public $active;
	protected $edition;
	protected $user;
	protected $school;
	protected $degree;
	protected $work_type;
	protected $files;

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

	public function getApplications($sort = NULL)
	{
		$sort = $sort != NULL ? array($sort,'application_date ASC') : 'application_date ASC';

		$rowset = $this->getDbTable()->getAllApplications($sort);

		$applications = array();
		foreach($rowset as $row)
		{
			$application = new $this;
			$applications[] = $application->populate($row);
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
		$this->__get('school');
		$data = array(
				'country' => $this->country,
				'application_id' => $this->application_id,
				'edition_id' => $this->edition_id,
				'user_id' => $this->user_id,
				'school' => $this->school->school_name,
				'department' => $this->department,
				'degree_id' => $this->degree_id,
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
		$file = $this->files[$index - 1];
		$path = $file->getImage('miniature');
		
		if (!file_exists(APPLICATION_PATH.'/../public/assets/applications/'.$path))
		{
			$this->createMiniature();
		}
		
		return $path;
	}

}

