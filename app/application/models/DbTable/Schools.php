<?php

class Application_Model_DbTable_Schools extends Zefir_Application_Model_DbTable
{

    protected $_raw_name = 'schools';
	protected $_name = '';
    protected $_primary = 'school_id';

    /**
	 * An array of parent table information
	 * @var array
	 */
	protected $_hasMany = array(
		'applications' => array(
			'model' => 'Application_Model_Applications',
			'refColumn' => 'school_id'
		)
	);
	
    /**
     * Find rows by the name column
     * @param string $name
     * @param Application_Model_Schools $school
     * @return Application_Model_Schools $school
     */
    public function findByName($name)
    {
    	$select = $this->select()->where('school_name = ?', $name);
    	$row = $this->fetchRow($select);
    	
    	return $row;
    }    
    
    public function findSchool($name)
    {
    	$select = $this->select()->where('school_name LIKE "%' . $name . '%"');
    	$rowset = $this->fetchAll($select);
    	return $rowset;
    }
}

