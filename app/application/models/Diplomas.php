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

	public function save()
	{
		$self = $this->getDbTable()->save($this);

		if (is_array($this->fields))
		{
			foreach($this->fields as $field)
			{
				$field->diploma_id = $self->diploma_id;
				$field->save();
			}
		}
		if (is_array($this->files)){
			foreach($this->files as $file)
			{
				$file->diploma_id = $self->diploma_id;
				$file->save();
			}
		}
		return $self;
	}

	public function getField($field, $lang)
	{
		if (!is_array($this->fields))
		$this->__get('fields');

		$options = Zend_Registry::get('options');
		$default_language = $options['i18n']['default_language'];

		foreach($this->fields as $diplomaField)
		{
			if ($diplomaField->field->field_name == $field && $diplomaField->lang->lang_code == $lang)
			$entry = $diplomaField->entry;

			if ($diplomaField->field->field_name == $field && $diplomaField->lang->lang_code == 'pl')
			$entry_pl = $diplomaField->entry;

			if ($diplomaField->field->field_name == $field && $diplomaField->lang->lang_code == $default_language)
			$entry_def_lang = $diplomaField->entry;
		}

		if (isset($entry) && $entry != '')
		return  $entry;
		elseif (isset($entry_def_lang) && $entry_def_lang != '')
		return $entry_def_lang;
		elseif (isset($entry_pl) && $entry_pl != '')
		return $entry_pl;
		else
		return null;
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

	public function prepareFormArray($lang)
	{
		$language = new Application_Model_Languages();

		return array(
			'diploma_id' => $this->diploma_id,
			'degree_id' => $this->degree_id,
			'lang_id' => $language->findLangId($lang),
			'work_subject' => $this->getField('work_subject', $lang),
			'work_desc' => $this->getField('work_desc', $lang),
			'school' => $this->getField('school', $lang),
			'department' => $this->getField('department', $lang),
			'supervisor' => $this->supervisor,
			'supervisor_degree' => $this->supervisor_degree,
			'name' => $this->name,
			'surname' => $this->surname,
			'email' => $this->email
		);
	}

	public function populateFieldsFromForm($data)
	{
		$field = new Application_Model_Fields();
		$id = $data['diploma_id'];
		$lang = $data['lang_id'];

		$this->name = $data['name'];
		$this->surname = $data['surname'];
		$this->email = $data['email'];
		$this->supervisor = $data['supervisor'];
		$this->supervisor_degree = $data['supervisor_degree'];
		$this->degree_id = $data['degree_id'] != 0 ? $data['degree_id'] : NULL;

		foreach($data['fields'] as $field_name => $entry)
		{
			$diplomaField = new Application_Model_DiplomaFields();
			$diplomaField->diploma_id = $id;
			$diplomaField->lang_id = $lang;
			$diplomaField->field_id = $field->getField($field_name)->field_id;
			$diplomaField->entry = $entry;
			$this->addChild($diplomaField, 'fields');
		}

		return $this;
	}

}

