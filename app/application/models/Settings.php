<?php

class Application_Model_Settings extends GP_Application_Model
{
	protected $_current_edition;
	protected $_template_default;
	protected $_max_file_size;
	protected $_date_format;
	protected $_max_files;
	protected $_work_start_date;
	protected $_work_end_date;
	protected $_application_deadline;
	protected $_result_date;
	
	protected $_dbTableModelName = 'Application_Model_DbTable_Settings';

	protected $_set_vars = array('_current_edition', '_template_default', '_max_file_size', 
								'_date_format', '_max_files', '_work_start_date', '_work_end_date', 
								'_application_deadline', '_result_date');
	protected $_get_vars = array('_current_edition', '_template_default', '_max_file_size', 
								'_date_format', '_max_files', '_work_start_date', '_work_end_date', 
								'_application_deadline', '_result_date');
	
	
	public function __construct(array $options = null) 
	{
	    parent::__construct($options);
	}
	
	public function prepareFormArray()
	{
		$data = array(
			'current_edition' => $this->_current_edition->_edition_id,
			'template_default' => $this->_template_default->_template_id,
			'max_file_size' => ($this->_max_file_size)/1024/1024,
			'date_format' => $this->_date_format,
			'max_files' => $this->_max_files,
			'work_start_date' => date('d-m-Y', $this->_work_start_date),
			'work_end_date' => date('d-m-Y', $this->_work_end_date),
			'application_deadline' => date('d-m-Y', $this->_application_deadline),
			'result_date' => date('d-m-Y', $this->_result_date),
		);
		
		return $data;
	}
	
	public function populateFromForm($data)
	{
		parent::populateFromForm($data);
		
		$this->_current_edition			= (int)$this->_current_edition;
		$this->_max_file_size			= ($this->_max_file_size) * 1024 * 1024;
		$this->_work_start_date 		= strtotime($this->_work_start_date);
		$this->_work_end_date 			= strtotime($this->_work_end_date);
		$this->_application_deadline 	= strtotime($this->_application_deadline);
		$this->_result_date			 	= strtotime($this->_result_date);
		
		return $this;
	}
}

