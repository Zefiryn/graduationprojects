<?php

class Application_Model_DbTable_Degrees extends Zefir_Application_Model_DbTable
{

    protected $_raw_name = 'degrees';
    protected $_name = '';
    protected $_primary = 'degree_id';

    /**
     * An array of child tables information
     * @var array
     */
    protected $_belongsTo = array();
	
	/**
	 * An array of parent table information
	 * @var array
	 */
	protected $_hasMany = array(
		'applications' => array(
			'model' => 'Application_Model_Applications',
			'refColumn' => 'degree_id'
		)
	);
	
	public function findDegree($degree)
	{
		$row = $this->fetchRow($this->select()->where('degree_name = ?', $degree));
		return $row;
	}

}

