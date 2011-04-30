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
    		$row = $this->find($id)->current();
    	
    	else
    	{
    		if ($id != null)
    			throw new Zend_Exception('Incorrect school');
    		else
    			$row = $this->createRow();
    	}
    	
    	$row->school_name = $school->_school_name;
    	
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
    
    /**
     * Find rows by the name column
     * @param string $name
     * @param Application_Model_Schools $school
     * @return Application_Model_Schools $school
     */
    public function findByName($name, $school)
    {
    	$row = $this->fetchRow($this->select()->where('school_name = ?', $name));
    	
    	if ($row)
    	{
	    	$school->populate($row);
    		$this->getChildren($row, $school);
    	}
    	
    	return $school;
    }    
}

