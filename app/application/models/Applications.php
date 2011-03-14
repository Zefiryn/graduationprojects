<?php

class Application_Model_Applications extends GP_Application_Model
{
	protected $_application_id;
	protected $_edition;
	protected $_user;
	protected $_country;
	protected $_school;
	protected $_department;
	protected $_degree;
	protected $_work_subject;
	protected $_work_type;
	protected $_work_desc;
	protected $_supervisor;
	protected $_supervisor_degree;
	protected $_graduation_time;
	protected $_application_date;
	protected $_miniature;
	protected $_files;
	protected $_active;	
	protected $_update = FALSE;
	
	protected $_dbTableModelName = 'Application_Model_DbTable_Applications';
	
	protected $_set_vars = array('_application_id', '_edition', '_user', '_country', 
								'_school', '_department', '_degree', '_work_subject', 
								'_work_type', '_work_desc', '_supervisor', 
								'_supervisor_degree', '_files', '_graduation_time', 
								'_application_date', '_miniature', '_active', 
								'_update');
	protected $_get_vars = array('_application_id', '_edition', '_user', '_country', 
								'_school', '_department', '_degree', '_work_subject', 
								'_work_type', '_work_desc', '_supervisor', 
								'_supervisor_degree', '_files', '_graduation_time', 
								'_application_date', '_miniature', '_active', 
								'_update');
	
	
	public function __construct($id = null, array $options = null) 
	{
	    return parent::__construct($id, $options);
	}
	
	public function populateFromForm($data)
	{
		$appSettings = Zend_Registry::get('appSettings');
		parent::populateFromForm($data);
		
		if (isset($data['new_school']) && $data['new_school'] != null)
			$this->_school = $data['new_school'];
		else
			$this->_school = (int) $this->_school;
		
		if ($this->_application_date == null)
			$this->_application_date = time();
		
		if ($this->_graduation_time != null)
		{
			$this->_graduation_time = strtotime($this->_graduation_time);
		}
		
		if ($this->_active == null)
			$this->_active = 1;
		
		$this->_miniature = $data['miniatureCache'];
		
		for($i = 1; $i <= $appSettings->_max_files; $i++)
		{//add uploaded files
			if ($data['file_'.$i]['file_'.$i.'Cache'] != null)
			{
				$this->_files[$i]['file'] = $data['file_'.$i]['file_'.$i.'Cache'];
				$this->_files[$i]['description'] = $data['file_'.$i]['file_annotation'];
			}  
		}
	}
	
	public function delete()
	{
		return $this->getDbTable()->delete($this);
	}
	
	public function getApplications($select, $arg)
	{
		switch($select)
		{
			case 'edition':
				return $this->_getApplicationsByEdition($arg);
			break;
		}
		
	}
	
	protected function _getApplicationsByEdition($id)
	{
		$where = $this->getDbTable()->select()->where('edition_id = ?', $id)->order('application_date');
		$rowset = $this->getDbTable()->fetchAll($where);
		
		$applications = array();
		foreach($rowset as $row)
		{
			$application = new $this;
			$applications[] = $application->populateWithReference($row, $application);
		}
		
		return $applications;
	}
	
	public function getApplicationById($id)
	{
		$row = $this->getDbTable()->find($id)->current();	
		return $this->populateWithReference($row);
	}
	
	public function getSupervisor()
	{
		return $this->_supervisor_degree.' '.$this->_supervisor;
	}
	
	public function getApplicationSchool()
	{
		return $this->_school->_school_name.', '.$this->_department;
	}
	
	public function prepareFormArray()
	{
		$data = array(
				'application_id' => $this->_application_id,
				'school' => $this->_school->_school_id,
				'department' => $this->_department,
				'degree' => $this->_degree->_degree_id,
				'work_subject' => $this->_work_subject,
				'work_type' => $this->_work_type->_work_type_id,
				'work_desc' => $this->_work_desc,
				'supervisor_degree' => $this->_supervisor_degree,
				'supervisor' => $this->_supervisor,
				'graduation_time' => date('d-m-Y', $this->_graduation_time),
				'miniatureCache' => $this->_miniature,
				'personal_data_agreement' => TRUE
		);
		
		foreach($this->_files as $no => $file)
		{
			$i = ++$no;
			$data['file_'.$i]['file_'.$i.'Cache'] = $file->_path;
			$data['file_'.$i]['file_annotation'] = $file->_file_desc;
		}
		
		return $data;
	}

}

