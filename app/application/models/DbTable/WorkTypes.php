<?php

class Application_Model_DbTable_WorkTypes extends Zefir_Application_Model_DbTable
{

	protected $_raw_name = 'work_types';
	protected $_name = '';
	protected $_primary = 'work_type_id';


	/**
	 * An array of parent table information
	 * @var array
	 */
	protected $_hasMany = array(
		'applications' => array(
			'model' => 'Application_Model_Applications',
			'refColumn' => 'work_type_id'
	)
	);

	public function findWorkType($name)
	{
		$select = $this->select()->where('work_type_name = ?', $name);
		$row = $this->fetchRow($select);

		return $row;
	}
}

