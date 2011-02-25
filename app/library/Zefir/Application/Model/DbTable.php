<?php
/**
 * @package Zefir_Application_Model_DbTable
 */
/**
 * 
 * Zend_Db_Table extension
 * @author Zefiryn
 * @since Jan 2011
 */
class Zefir_Application_Model_DbTable extends Zend_Db_Table_Abstract
{

	/**
	 * Constructor
	 * @access public
	 * @param array $config
	 * @return void
	 */
  	public function __construct($config = array())
  	{
    	$options = Zend_Registry::get('options');
    	$this->_name = $options['resources']['db']['params']['prefix'].$this->_raw_name;
    	parent::__construct();
  	}

  	/**
  	 * Get the reference map array
  	 * @access public
  	 * @return array an array of related tables
  	 */
	public function getReferenceMap()
	{
		return $this->_referenceMap;
	}
  	
	/**
	 * 
	 * Get data from parent table
	 * @param Zend_Db_Table_Row $result
	 * @param Zefir_Application_Model $object
	 * @return Zefir_Appication_Model $object
	 */
	public function getParents(Zend_Db_Table_Row $result, Zefir_Application_Model $object)
	{
		foreach($this->getReferenceMap() as $name => $tableData)
		{
			$model_name = 'Application_Model_'.$name;
			$parentObject = new $model_name; 
			$object->$tableData['objProperty'] = $parentObject->populate($result->findParentRow($tableData['refTableClass']));
		}
		
		return $object;
	}
	
	/**
	 * 
	 * Get data from dependent tables
	 * @param Zend_Db_Table_Row $result
	 * @param Zefir_Application_Model $object
	 * @return Zefir_Appication_Model $object
	 */
	public function getChildren(Zend_Db_Table_Row $result, Zefir_Application_Model $object)
	{

		foreach($this->getDependentTables() as $property => $class)
		{
			$rowset = $result->findDependentRowset($class);				
			$set = array();			
			
			if (isset($this->_hasMany[$property]))
			{
				//$rowset has rows from the joining table 				
				foreach ($rowset as $row)
				{
					//create Zend_Db_Table_Abstract class of the associated table
					$assocData = new $this->_hasMany[$property]['dbModel'];
					
					//get name of the column that has the reference to the associated table 
					$column = $this->_hasMany[$property]['column'];
					
					//create new model of a associated data
					$propertyModel = new $this->_hasMany[$property]['assocModel'];
					 
					//populate model with data find by the column_id of the associated table
					$set[] = $propertyModel->populate($assocData->find($row->$column)->current()); 
				}		

			}
			
			else
			{
				$model = str_replace('DbTable_', '', $class);
				foreach ($rowset as $row)
				{
					$data = new $model;
					$set[] = $data->populate($row);
				}
			}
			
			$object->$property = $set;
		}

		return $object;
	}
	
	
	/**
	 * Search the db for the records fitting given data
	 * @access public
	 * @param array $data
	 * @param Zefir_Application_Model $model
	 * @return array an array of found objects
	 */
	public function dataExist($data, $model)
	{
		$select = $this->select();
		
		/**
		 * $data is an associtive array which has field name as a key and its value as value
		 * the comparison is strict 
		 */
		foreach($data as $field => $value)
			$select->where($field.' = ?', $value);
		
		$rowset = $this->fetchAll($select);
		
		$array = array();
		foreach($rowset as $row)
		{
			$model->populate($row);

			// Get class depenedt data
			if (count($this->_dependentTables) > 0)
			{
				$this->getChildren($row, $model);
			}
			if (count($this->_referenceMap) > 0)
			{
				$this->getParents($row, $model);
			}
			
			$array[] = $model;
		}
		return $array;
		
	}
	
	public function getTableName()
	{
		return $this->_name;
	}
}
