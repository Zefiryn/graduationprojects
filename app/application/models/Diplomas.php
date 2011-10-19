<?php

class Application_Model_Diplomas extends GP_Application_Model
{
	public $diploma_id;
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
	
	protected $_dbTableModelName = 'Application_Model_DbTable_Diplomas';
	
	public function __construct($id = null, array $options = null) 
	{
	    return parent::__construct($id, $options);
	}
	
	/**
	 * Add new field for this application
	 * 
	 * @access public
	 * @param Application_Model_DiplomaFields $field
	 * @return Applcation_Model_Diplomas $this
	 */
	public function addChild(Zefir_Application_Model $object, $var)
	{
		if (!is_array($this->$var))
			$this->$var = array();
			
		array_push($this->$var, $object);

		return $this;
	}
	
	public function save()
	{
		$self = $this->getDbTable()->save($this);
		
		foreach($this->fields as $field)
		{
			$field->diploma_id = $self->diploma_id;
			$field->save();
		}
		
		foreach($this->files as $file)
		{
			$file->diploma_id = $self->diploma_id;
			$file->save();
		}
		
		return $self;	
	}
	
	public function getField($field, $lang)
	{
		if (!is_array($this->fields))
			$this->__get('fields');
				
		foreach($this->fields as $diplomaField)
		{
			if ($diplomaField->field->field_name == $field && $diplomaField->lang->lang_code == $lang)
				return $diplomaField->entry;
		}
	}
	
	public function getAuthorName()
	{
		return $this->name.' '.$this->surname;
	}
	
	public function getSupervisor()
	{
		return $this->supervisor_degree.' '.$this->supervisor;
	}
	
	public function getAdjacentDiplomas()
	{
		return $this->getDbTable()->getAdjacentDiplomas($this);
	}

}

