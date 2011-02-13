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
}

