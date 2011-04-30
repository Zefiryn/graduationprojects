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
	protected $_belongsTo = array();
	protected $_hasMany = array();
	protected $_prefix;
	
	/**
	 * Constructor
	 * @access public
	 * @param array $config
	 * @return void
	 */
  	public function __construct($config = array())
  	{
    	$options = Zend_Registry::get('options');
    	$this->_prefix = $options['resources']['db']['params']['prefix'];
    	$this->_name = $this->_prefix.$this->_raw_name;
    	parent::__construct($config);
  	}

  	/**
  	 * Get the reference map array
  	 * @access public
  	 * @return array an array of related tables
  	 */
	public function getHasMany()
	{
		return $this->_hasMany;
	}
	
	public function getBelongsTo()
	{
		return $this->_belongsTo;
	}
	
	/**
	 * Get the name of the assosiated table in the database
	 * 
	 * @access public
	 * @return string
	 */
	public function getTableName()
	{
		return $this->_name;
	}
	
	public function getPrimaryKey()
	{
		if (is_array($this->_primary))
		{
			$primary = reset($this->_primary);
			return $primary;
		}
		else
			return $this->_primary;
	}
	  	
	public function getChild($result, $property)
	{
		$primary = $this->getPrimaryKey();
		$prefix = $this->_prefix;
		
		$assocData = $this->_hasMany[$property];
		$dependentObject = new $assocData['model'];
		
		//reference is in the associated model table
		if (!isset($assocData['joinTable']) && !isset($assocData['joinModel']))
		{
			$dependentObjectTable =  $dependentObject->getDbTable(); 
			$dependentSelect = $dependentObjectTable->select()->where($assocData['refColumn']. '= ?', $result->$primary);
		}
		
		//join throught table without extra data in joining table
		elseif (isset($assocData['joinTable']))
		{
			
			$joinTable = $prefix.$assocData['joinTable'];
			$dependentObjectTable =  $dependentObject->getDbTable(); 
			$dependentSelect = $dependentObjectTable->select()->setIntegrityCheck(FALSE);
			
			$dependentSelect->from(array('t' => $dependentObjectTable->getTableName()))
							->join(array('jt' => $joinTable),
										't.'.$dependentObjectTable->getPrimaryKey().' = jt.'.$assocData['joinRefColumn'])
							->where('jt.'.$assocData['refColumn']. '= ?', $result->$primary);
		}
		
		//join through table with extra data in joining table
		elseif (isset($assocData['joinModel']))
		{
			$joinModel = new $assocData['joinModel'];
			$joinTable = $joinModel->getDbTable()->getTableName();
			
			$dependentObjectTable =  $dependentObject->getDbTable();
			$dependentSelect = $dependentObjectTable->select()->setIntegrityCheck(FALSE);
			$dependentSelect->from(array('t' => $dependentObjectTable->getTableName()))
							->join(array('jt' => $joinTable),
										't.'.$dependentObjectTable->getPrimaryKey().' = jt.'.$assocData['joinRefColumn'])
							->where('jt.'.$assocData['refColumn']. '= ?', $result->$primary);
		}
		
		//set order
		if (isset($assocData['order']))
				$dependentSelect->order($assocData['order']);
		
		//find rows and create table
		$rowset = $this->fetchAll($dependentSelect);
			
		$set = array();
		foreach($rowset as $row)
		{
			$model = new $assocData['model'];
			$set[] = $model->populate($row);
		}
		return $set;
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
	
	/**
	 * Create new object of a given model
	 * 
	 * @param Zefir_Application_Model $model
	 * @return Zefir_Application_Model
	 */
	private function _createNewModel(Zefir_Application_Model $model)
    {
        $class = get_class($model);
        return new $class;
    }
    
	/**
	 * Copy file from the cache folder
	 * 
	 * @access private
	 * @param string $source
	 * @param string $destination
	 * @return boolean
	 */
	protected function _copy($source, $destination)
	{
		$options = Zend_Registry::get('options');
		$dir = substr($options['upload']['cache'], -1) == '/' ? $options['upload']['cache'] : $options['upload']['cache'].'/';
		$source = APPLICATION_PATH.'/../public'.$dir.$source;
		
		$destination = APPLICATION_PATH.'/../public'.$destination;
		
		$copied = rename($source, $destination);
		
		if ($copied)
			chmod($destination, 0666);
			
		return $copied;
	}
	
	/**
	 * Select new name for an existing file
	 * @access private
	 * @param string $dir
	 * @param string $file
	 * @return string $name
	 */
	protected function _getNewName($dir, $file)
	{
		$ext = substr($file, strrpos($file, '.'));
		$rawname = substr($file, 0, strrpos($file, '.'));
		$name = '';
		
		for($i = 1; $name == ''; $i++)
		{
			$tryname = $rawname.'_'.$i.$ext;
			if (!file_exists($dir.$tryname))
				$name = $tryname;
		}
		
		return $name;
	}
	
	/**
	 * Prepare delete where string and call Zend_Db_Table_Abstract delete method
	 * 
	 * @access public
	 * @param Zefir_Application_Model $object
	 * @return void
	 */
	public function delete(Zefir_Application_Model $object)
    {
    	$column = $this->_primary;

    	$column = is_array($column) ? $column : array($column);
    	
    	foreach($column as $col)
    	{
    		$property = '_'.$col;
       		$where = $this->getAdapter()->quoteInto($col.' = ?', $object->$property);
    	}

    	parent::delete($where);
    }
    
    
    /**
     * Save object data in the database
     *	
     * @access public
     * @param Zefir_Application_Model $object
     * @return Zefir_Application_Model $object
     */
    public function save(Zefir_Application_Model $object)
    {
    	//get the name of the primary column
    	$primary = is_array($this->_primary) ? $this->_primary[1] : $this->_primary;
    	
    	//create name of the property that holds primary column data
    	$id = $object->$primary;
    	
    	//get the row from the databases
    	$row = $this->find($id)->current();
    	
    	if (!$row)
    		$row = $this->createRow();
    	
    	//get table columns but remove primary column
    	$columns = $row->toArray();
    	unset($columns[$primary]);
    	
    	foreach($columns as $name => $value)
    	{
    		$row->$name = $object->$name;
    	}

    	if ($row->save())
    	{
    		if ($id == null)
    			$object->$primary = $id = $this->getAdapter()->lastInsertId();
    	}
    	else 
    	{
    		throw new Zend_Exception('Couldn\'t save data');
    	}

    	return $object;
    }
    
    public function countParents($refColumn, $refValue)
    {
    	$select = $this->select()->from(array($this->_name), array('number' => 'count(*)'))->where($refColumn.'=?', $refValue);
    	$row = $this->fetchRow($select);
    	
    	return ($row->number);

    }
    
	public function getRowsNum()
	{
		$select = $this->select()->from(array($this->_name),
										array('rows' => 'COUNT(*)'));
										
		$row = $this->fetchRow($select);
		
		return $row->rows;
	}
}
