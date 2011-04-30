<?php

class Application_Model_Settings extends GP_Application_Model
{
	public $current_edition;
	public $template_default;
	public $max_file_size;
	public $date_format;
	public $max_files;
	public $work_start_date;
	public $work_end_date;
	public $application_deadline;
	public $result_date;
	protected $edition;
	protected $template;
	
	protected $_dbTableModelName = 'Application_Model_DbTable_Settings';

	public function __construct($id = null, array $options = null) 
	{
	    return parent::__construct($id, $options);
	}
	
	public function prepareFormArray()
	{
		$data = array(
			'current_edition' => $this->current_edition,
			'template_default' => $this->template_default,
			'max_file_size' => ($this->max_file_size)/1024/1024,
			'date_format' => $this->date_format,
			'max_files' => $this->max_files,
			'work_start_date' => date('d-m-Y', $this->work_start_date),
			'work_end_date' => date('d-m-Y', $this->work_end_date),
			'application_deadline' => date('d-m-Y', $this->application_deadline),
			'result_date' => date('d-m-Y', $this->result_date),
		);
		
		return $data;
	}
	
	public function populateFromForm($data)
	{
		parent::populateFromForm($data);
		
		$this->current_edition			= (int)$this->current_edition;
		$this->max_file_size			= ($this->max_file_size) * 1024 * 1024;
		$this->work_start_date 			= strtotime($this->work_start_date);
		$this->work_end_date 			= strtotime($this->work_end_date);
		$this->application_deadline 	= strtotime($this->application_deadline);
		$this->result_date			 	= strtotime($this->result_date);
		
		return $this;
	}
}

