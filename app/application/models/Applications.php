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
	
	
	public function __construct(array $options = null) 
	{
	    parent::__construct($options);
	}
	
	public function populateFromForm($data)
	{
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
	}

}

