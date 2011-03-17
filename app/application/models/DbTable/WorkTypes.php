<?php

class Application_Model_DbTable_WorkTypes extends Zefir_Application_Model_DbTable
{

    protected $_raw_name = 'work_types';
	protected $_name = '';
    protected $_primary = 'work_type_id';

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
	
	public function save(Application_Model_WorkTypes $type)
	{
		$id = $type->_work_type_id;
		
		$row = $this->find($id)->current();
		
		if (!$row)
			$row = $this->createRow();
			
		$row->work_type_name = $type->_work_type_name;
		
		if ($row->save())
		{
			if (!$id)
    			$type->_work_type_id = $id = $this->getAdapter()->lastInsertId();
		}
		else
		{
			throw new Zend_Exception('Couldn\'t save data');
		}
		
		
		return $type;
	}
}
