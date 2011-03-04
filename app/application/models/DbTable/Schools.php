<?php

class Application_Model_DbTable_Schools extends Zefir_Application_Model_DbTable
{

    protected $_raw_name = 'schools';
	protected $_name = '';
    protected $_primary = 'school_id';

    /**
     * An array of child tables information
     * @var array
     */
    protected $_referenceMap = array();
	
	/**
	 * An array of parent table information
	 * @var array
	 */
	protected $_dependentTables = array();
	
	/**
     * Save or update school data in the database 
     * 
     * @param Application_Model_School $school
     * @throws Zend_Exception
     * @return Application_Model_School $school
     */
    public function save(Application_Model_Schools $school)
    {
    	$id = $school->_school_id;
    	
    	if ($id != null)
    		$row = $this->find($id);
    	
    	else
    	{
    		if ($id != null)
    			throw new Zend_Exception('Incorrect school');
    		else
    			$row = $this->createRow();
    	}
    	
    	$row->school_name	= $school->_school_name;
    	
    	if ($row->save())
    	{
    		if (!$id)
    			$school->_school_id = $id = $this->getAdapter()->lastInsertId();
    	}
    	else 
    	{
    		throw new Zend_Exception('Couldn\'t save data');
    	}
    	return $school;
    }
}

