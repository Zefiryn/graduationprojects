<?php

class Application_Model_Results extends GP_Application_Model
{
	public $result_id;
	public $edition_id;
	public $name;
	public $surname;
	public $email;
	public $country;
	public $degree_id;
	public $work_type_id;
	public $graduation_time;
	public $supervisor;
	public $supervisor_degree;
	protected $edition;
	protected $degree;
	protected $files;
	protected $work_type;
	protected $fields;
	
	protected $_dbTableModelName = 'Application_Model_DbTable_Results';
	
	public function __construct($id = null, array $options = null) 
	{
	    return parent::__construct($id, $options);
	}
	
	/**
	 * Add new field for this application
	 * 
	 * @access public
	 * @param Application_Model_ResultFields $field
	 * @return Applcation_Model_Results $this
	 */
	public function addChild(Zefir_Application_Model $object, $var)
	{
		if (!is_array($var))
			$this->$var = array();
		array_push($this->$var, $object);
	}
	
	public function save()
	{
		$self = $this->getDbTable()->save($this);
		
		foreach($this->fields as $field)
		{
			$field->result_id = $self->result_id;
			$field->save();
		}
		
		foreach($this->files as $file)
		{
			$file->result_id = $self->result_id;
			$file->save();
		}
		
		return $self;	
	}

}

