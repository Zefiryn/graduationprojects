<?php
/**
 * @package Zefir_Application_Model
 */
/**
 * Main class for all models
 * @author zefiryn
 * @since Jan 2011
 *
 */
class Zefir_Application_Model {
	
	/**
	 * @var Zend_Db_Table
	 */
	protected $_dbTable;
	/**
	 * @var string
	 */
	protected $_dbTableModelName;
	/**
	 * @var boolean
	 */
	protected $_externalModel = FALSE;
     
	/**
	 * Constructor
	 * 
	 * @access public
	 * @param array $options
	 * @param int $id
	 * @throws Exception
	 * @return void
	 */
	public function __construct($id = NULL, $options = NULL) 
	{
	    if ( null != $this->_dbTableModelName ) 
	    {
			$this->setDbTable($this->_dbTableModelName);
	    }
	    else 
	    {
	    	if (FALSE == $this->_externalModel)
				throw new Exception('Invalid table data gateway provided');
	    }
	    
		if (is_array($options)) 
	    {
			$this->setOptions($options);
	    }
	    
	    if ($id != null)
	    {
	    	$row = $this->getDbTable()->find($id)->current();
	    	$this->populateWithReference($row);	
	    }
	    
	    return $this;
	}
	
	
	/**
	 * Set Zend_Db_Table object for current model
	 * 
	 * @access public
	 * @param Zend_Db_Table_Abstract $dbTable
	 * @throws Exception
	 * @return Zefir_Application_Model_Mapper $this
	 */
	public function setDbTable($dbTable) 
	{
	    if (is_string($dbTable)) 
	    {
			$dbTable = new $dbTable();
	    }
	    if (!$dbTable instanceof Zend_Db_Table_Abstract) 
	    {
			throw new Exception('Invalid table data gateway provided');
	    }
	    $this->_dbTable = $dbTable;
	    return $this;
	}
     
	/**
	 * Get current Zend_Db_Table object
	 * 
	 * @access public
	 * @return Zend_Db_Table $_dbTable
	 */
	public function getDbTable() 
	{
	    if (null === $this->_dbTable) 
	    {
			$this->setDbTable($this->_dbTableModelName);
	    }
	    return $this->_dbTable;
	}
 
	/**
	 * Magic function for setting properties of the object
	 * 
	 * @access public
	 * @param string $name
	 * @param mixed $value
	 * @throws Exception
	 * @return void
	 */
	public function __set($name, $value) 
	{
	    if (!in_array($name, $this->_set_vars)) 
	    {
			throw new Exception('Variable '.$name.' cannot be set from the outside of the class');
	    }
	    else
	    {
	    	$this->$name = $value;
	    }
	}
 
	/**
	 * Magic function for getting properties of the object
	 * 
	 * @access public
	 * @param string $name
	 * @throws Exception
	 * @return mixed
	 */
	public function __get($name) 
	{

	    if (!in_array($name, $this->_get_vars)) 
	    {
			throw new Exception('Variable '.$name.' cannot be read from the outside of the class');
	    }
	    else
	    {
	    	return $this->$name;
	    }
	}
 
	/**
	 * Set multiple object properties at once
	 * 
	 * @access public
	 * @param array $options
	 * @return Zefirn_Application_Model $this
	 */
	public function setOptions(array $options) 
	{
	    foreach ($options as $key => $value) 
	    {
	    	$var = '_'.$key;
	    	if (property_exists($this, $var))
				$this->$var = $value;
	    }
	    return $this;
	}
	       
	/**
	 * Convert the object to an array
	 * 
	 * @access public
	 * @return array $array
	 */
	public function toArray() 
	{
            
    	$array = array();
    	$properties = $this->getProperties();
            
	    foreach($properties as $property) 
	    {
    	    if($property->isProtected()) 
    	    {
            	$key = substr($property->getName(),1);
	            $var = '_' . $key;
    	        $array[$key] = $this->$var;
        	}
                
    	}
    	return $array;
	}
	
	/**
	 * Populate object with data from the Zend_Db_Table_Row
	 * 
	 * @access public
	 * @param Zend_Db_Table_Row|array $row
	 * @return Zefir_Application_Model $this
	 */
	public function populate($row)
	{
		foreach ($this->_set_vars as $var)
		{
			$var_raw = substr($var, 1);
			if (isset($row->$var_raw))
				$this->$var = $row->$var_raw;
				
			if 	(isset($row[$var_raw]))
				$this->$var = $row[$var_raw];
		}
		
		return $this;
	}
	
	/**
	 * Populate object with data from row and perform
	 * 
	 * @access public
	 * @param Zend_Db_Table_Row $row
	 * @return Zefir_Application_Model
	 */
	public function populateWithReference($row)
	{
		foreach ($this->_set_vars as $var)
		{
			$var_raw = substr($var, 1);
			if (isset($row->$var_raw))
				$this->$var = $row->$var_raw;
				
			if 	(isset($row[$var_raw]))
				$this->$var = $row[$var_raw];
		}
		
		if ($row)
		{
			$this->getDbTable()->getChildren($row, $this);
			$this->getDbTable()->getParents($row, $this);
		}
		return $this;
	}
	
	/**
	 * Compare function used with usort function
	 * 
	 * @access private
	 * @param mixed $a
	 * @param mixed $b
	 * @param array $properties
	 * @return int
	 */
	protected function _compareObjects($a, $b, $properties)
	{
		foreach($properties as $property)
		{
			if (mb_strtolower($a->$property, 'UTF8') <> mb_strtolower($b->$property, 'UTF8'))
			{
				return (mb_strtolower($a->$property, 'UTF8') < mb_strtolower($b->$property, 'UTF8')) ? -1 : 1;
			}
		}
		
		//if none non-zero value was returned, return 0
		return 0;
		
	}
	
	/**
	 * Check if the object has been populated
	 * @access public
	 * @return boolean
	 */
	public function isEmpty()
	{
		$id_var = $this->_set_vars[0];

		return (($this->$id_var == NULL) ? TRUE : FALSE);
	}
	
	/**
	 * Get form data and populate object with it; references are not objects but id's of 
	 * rows in associated tables
	 * 
	 * @access public
	 * @param array $data
	 * @return Zefir_Application_Model
	 */
	public function populateFromForm($data)
	{
		foreach ($data as $key => $value)
		{
			$var = '_'.$key;
			if (in_array($var, $this->_set_vars))
				$this->$var = $value;
		}
		
		return $this;
	}
	
	/**
	 * Save object in the database
	 * 
	 * @access public
	 * @return void
	 */
	public function save()
	{
		return $this->getDbTable()->save($this);
	}
	
	/**
	 * Delete object from the database
	 * 
	 * @access public
	 * @return void
	 */
	public function delete()
	{
		$this->getDbTable()->delete($this);
	}
}
?>