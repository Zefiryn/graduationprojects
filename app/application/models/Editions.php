<?php

class Application_Model_Editions extends GP_Application_Model
{
	public $edition_id;
	public $edition_name;
	public $edition_name2;
	protected $applications;
	protected $diplomas;

	protected $_dbTableModelName = 'Application_Model_DbTable_Editions';

	public function __construct($id = null, array $options = null)
	{
		return parent::__construct($id, $options);
	}

	public function editionExists($edition, $onlyName = FALSE)
	{
		$this->getEdition($edition, $onlyName);

		if ($this->edition_id != NULL)
		$check = TRUE;
		else
		$check = FALSE;

		return $check;
	}

	public function getEditions($order = 'ASC')
	{
		$select = $this->getDbTable()->select()->order('edition_name '.$order);
		$rowset = $this->getDbTable()->fetchAll($select);

		$editions = array();
		foreach($rowset as $row)
		{
			$editions[$row->edition_id] = $row->edition_name;
		}

		return $editions;
	}


	public function getEdition($edition, $onlyName = FALSE)
	{
		if (ctype_digit($edition) && !$onlyName)
		$row = $this->getDbTable()->find($edition)->current();

		else
		$row = $this->getDbTable()->fetchRow($this->getDbTable()->select()->where('edition_name = ? ', $edition));

		if ($row)
		{
			$this->populate($row);
		}
		return $this;
	}

	public function getEditionByName($edition)
	{
		$where = $this->getDbTable()->select()->where('edition_name = ?', $edition);
		$row = $this->getDbTable()->fetchRow($where);

		if ($row)
		{
			$this->populate($row);
		}

		return $this;
	}

	public function prepareFormArray()
	{
		$data = array(
			'edition_id' => $this->edition_id,
			'edition_name' => $this->edition_name
		);

		return $data;
	}

	public function getDiplomas()
	{
		$diplomaTable = new Application_Model_DbTable_Diplomas();
		$diplomaFieldsTable = new Application_Model_DbTable_DiplomaFields();
		$diplomaFilesTable = new Application_Model_DbTable_DiplomaFiles();
		$fields = new Application_Model_DbTable_Fields();
		$langs = new Application_Model_DbTable_Languages();

		$select = $this->getDbTable()->select()->setIntegrityCheck(false);
		$select->from(array('e' => $this->getDbTable()->getTableName()))
			->joinLeft(array('d' => $diplomaTable->getTableName()), 'd.edition_id = e.edition_id')
			->joinLeft(array('fd' => $diplomaFieldsTable->getTableName()), 'fd.diploma_id = d.diploma_id')
			->joinLeft(array('fi' => $diplomaFilesTable->getTableName()), 'fi.diploma_id = d.diploma_id')
			->joinLeft(array('f' => $fields->getTableName()), 'f.field_id = fd.field_id')
			->joinLeft(array('l' => $langs->getTableName()), 'fd.lang_id = l.lang_id')
			->where('e.edition_id = ?', $this->edition_id)
			->order(array('surname ASC', 'name ASC', 'fi.position ASC'));


		$diplomas = array();
		$fields = array();
		foreach ($diplomaTable->fetchAll($select) as $row)
		{
			if (!isset($diplomas[$row['diploma_id']]))
			{
				$diploma = new Application_Model_Diplomas();
				$diploma->populate($row);
			}
			if (!isset($fields[$row['diploma_field_id']]))
			{
				$diplomaField = new Application_Model_DiplomaFields();
				$diplomaField->populate($row);

				$field = new Application_Model_Fields();
				$field->populate($row);
				$diplomaField->addParent($field, 'field');

				$lang = new Application_Model_Languages();
				$lang->populate($row);
				$diplomaField->addParent($lang, 'lang');

				$diploma->addChild($diplomaField, 'fields');
				$fields[$row['diploma_field_id']] = TRUE;
			}
				
			$diplomaFile = new Application_Model_DiplomaFiles();
			$diplomaFile->populate($row);
			$diploma->addChild($diplomaFile, 'files');
				
			$diplomas[$row['diploma_id']] = $diploma;
		}

		return $diplomas;
	}
}

