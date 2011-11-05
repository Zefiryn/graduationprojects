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
	  	
	/**
	 * Return children rowset
	 * 
	 * @param Zefir_Application_Model $object
	 * @param string $property
	 */
	public function getChild($object, $property)
	{
		$primary = $this->getPrimaryKey();
		$prefix = $this->_prefix;
		
		$assocData = $this->_hasMany[$property];
		$dependentObject = new $assocData['model'];
		
		//reference is in the associated model table
		if (!isset($assocData['joinTable']) && !isset($assocData['joinModel']))
		{
			$dependentObjectTable =  $dependentObject->getDbTable(); 
			$dependentSelect = $dependentObjectTable->select()->where($assocData['refColumn']. '= ?', $object->$primary);
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
							->where('jt.'.$assocData['refColumn']. '= ?', $object->$primary);
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
							->where('jt.'.$assocData['refColumn']. '= ?', $object->$primary);
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
	 * Copy file submited by the form
	 * 
	 * @access public
	 * @param Zefir_Application_Model $object
	 * @param string $property
	 * @param string $upload_dir
	 * @param Zefir_Application_Model $oldData
	 * @throws Zend_Exception
	 * @return Zefir_Application_Model
	 */
	protected function _copyFile($object, $property, $upload_dir, $uploadedFileName, $oldData)
    {
    	$copy = TRUE;
    	
    	$cachedFile = strstr($object->$property, '/') ? 
    		substr($object->$property, strpos($object->$property, '/') + 1) :	$object->$property;

    	if ($oldData != null)
    	{
    		//no new file has been sent
    		if ($oldData->$property == $cachedFile)
    			$copy = FALSE;
    	}
    	
    	if ($copy)
    	{
    		//copy new file
	    	$extension = Zefir_Filter::getExtension($object->$property);
			$dirName = APPLICATION_PATH.'/../public'.$upload_dir.'/';
	    	
    		$fileName = $this->_getNewName($dirName , $uploadedFileName.'.'.$extension);
	    	$dir = substr($upload_dir, -1) == '/' ? $upload_dir : $upload_dir.'/';
	    	
	    	if ($this->_copy($cachedFile, $dir.$fileName))
	    	{
	    		$object->$property = $fileName;
	    		
	    		//get thumbnails data and create it
	    		foreach($object->getThumbnails() as $key)
	    		{
	    			$this->_resize($object, $property, APPLICATION_PATH.'/../public'.$dir, $key);
	    		}
	    		
	    		if ($oldData != null)
	    		{//delete old image
	    			$this->_deleteOldImage($oldData);
	    		}
	    	}
	    	else
	    	{
	    		if ($oldData != null)
	    		{
	    			throw new Zend_Exception('Couldn\'t save file');
	    		}
	    	}
    	}
    	else
    	{
    		$object->$property = substr($object->$property, strpos($object->$property, '/') + 1); 
    	}
    	
    	return $object;
    }
    
    /**
     * Create image thumbnail
     * 
     * @param Zefir_Application_Model $object
     * @param string $property
     * @param sitr $dir
     * @param string $key
     * @throws Zend_Exception
     */
	protected function _resize(Zefir_Application_Model $object, $property, $dir, $key) 
	{
		//get image dimensions
		$imagedata = @getimagesize($dir.$object->$property);
		if(!$imagedata) {
			throw new Zend_Exception('Nie udało się odczytać rozmiarów orginalnej fotografii.');
		}

		$oldWidth = (int)$imagedata[0];
		$oldHeight = (int)$imagedata[1];

		$sourceX = 0;
		$sourceY = 0;
		
		//set dimensions for the thumbnail
		$newHeight = $oldHeight;
		$newWidth = $oldWidth;
		
		$sourceHeight = $oldHeight;
		$sourceWidth = $oldWidth;
		
		//get thumbnail settings
		$resizeData = $object->getImageData($key);
		$height = $resizeData['height'];
		$width = $resizeData['width'];
		$crop = $resizeData['crop'];
		
		//set paths to the files
		$inputFileName = $dir.$object->$property;
		$ext = Zefir_Filter::getExtension($object->$property);
		$outputFileName = $dir.str_replace('.'.$ext, '_'.$key.'.'.$ext, $object->$property);
		
		if($oldHeight <= $height && $oldWidth <= $width) 
		{
			//image is smaller than the thumbnail; do not resize, just copy
			$newHeight = $oldHeight;
			$newWidth = $oldWidth;
		} 
		elseif(isset($resizeData['ratio'])) 
		{
			//calculate new dimension according to ratio
			if ($resizeData['ratio'] == 'width')
			{
				$newWidth = $width;
				$newHeight = $this->_setRatio($oldWidth, $width, $oldHeight); 
			}
			else
			{
				$newHeight = $height;
				$newWidth = $this->_setRatio($oldHeight, $height, $oldWidth);
			}
		} 
		else 
		{
			if($crop) 
			{
				$sourceWidth = round($oldHeight / $height * $width);
				$sourceX = round(($oldWidth - $sourceWidth) / 2);
				$newWidth = $width;
				$newHeight = $height;
			} 
			else 
			{
				$newWidth = $width;
				$newHeight = round(($oldHeight * $newWidth) / $oldWidth);
			}
		}
		
		switch($imagedata['mime']) 
		{
			case 'image/jpeg': 
				$createFunction = 'imagecreatefromjpeg';
				$saveFunction = 'imagejpeg';
				$quality = 90;
				break;
			case 'image/png': 
				$createFunction = 'imagecreatefrompng';
				$saveFunction = 'imagepng';
				$quality = 0;
				break;
			case 'image/gif': 
				$createFunction = 'imagecreatefromgif';
				$saveFunction = 'imagegif';
				$quality = null;
				break;
			default:
				throw new Zend_Exception('Załadowano nieobsługiwany typ pliku.');
		}

		//przetwarzanie obrazu
		$image2 = imagecreatetruecolor($newWidth, $newHeight);
		$image1 = $createFunction($dir.$object->$property);
		
		//set full alpha transparency for png files
		if ($imagedata['mime'] == 'image/png')
		{
			imagealphablending($image2, false);
			imagealphablending($image1, false);
			imagesavealpha($image2, true);
			imagesavealpha($image1, true);			
		}
		
		if(!$image1 || !$image2) 
		{
			throw new Zend_Exception('Tworzenie miniatury nie powiodło się');
		}
		
		if($newHeight > $oldHeight && $newWidth > $oldWidth) 
		{
			// Jeśli nadesłany obrazek jest mniejszy niż nakazane wymiary, to przenoszony 
			//jest plik, żeby pominąć phpowe funkcje, które robią jakieś rozmycia podobdno
			if(!copy($inputFileName, $outputFileName)) 
			{
				throw new Zend_Exception('Kopiowanie pliku nie powiodło się. '.$outputFileName);
			}
		} 
		else 
		{
			if(!@imagecopyresampled($image2, $image1, 0, 0, $sourceX, $sourceY, $newWidth, $newHeight, $sourceWidth, $sourceHeight)) 
			{
				throw new Zend_Exception('Tworzenie miniatury nie powiodło się.2');
			}
		
			//zapisywanie miniaturki
			if(!$saveFunction($image2, $outputFileName, $quality)) 
			{
				throw new Zend_Exception('Zapisywanie miniatury nie powiodło się.3.'.$outputFileName);
			}
		}
		
		return true;
	}
	
	/**
	 * Set an image dimension to save ration according to the fixed second diemnsion
	 *  
	 * @param integer $imageSetDimension
	 * @param integer $newSetDimension
	 * @param integer $imageChangedDimension
	 * @return integer $newChangedDimension
	 */
	protected function _setRatio($imageSetDimension, $newSetDimension, $imageChangedDimension)
	{
		$scale = $newSetDimension/$imageSetDimension;
		$newChangedDimension= floor($imageChangedDimension * $scale);
		return $newChangedDimension;
	}
	
	/**
	 * Delete all old images
	 * 
	 * @param Zefir_Application_Model $object
	 */
	protected function _deleteOldImage(Zefir_Application_Model $object)
	{
		$property = $object->_image['property'];
		foreach ($object->getThumbnails() as $style)
		{
			unlink(APPLICATION_PATH.'/../public'.$object->_image['dir'].'/'.$object->getImage($style));
		}
		unlink(APPLICATION_PATH.'/../public'.$object->_image['dir'].'/'.$object->$property);
		
		return TRUE;
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
		//cut filename to 20 characters
		if (strlen($rawname) > 20)
			$rawname = substr($rawname, 0, 18);
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
       		$where = $this->getAdapter()->quoteInto($col.' = ?', $object->$col);
    	}
		
    	return parent::delete($where);
    }
    
    /**
     * Delete rows from the database using just zend_db delete method
     *  
     * @access public
     * @param mixed $where
     * @return void;
     */
    public function simpleDelete($where)
    {
    	return parent::delete($where);
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
    	$primary = is_array($this->_primary) ? array_shift($this->_primary) : $this->_primary;
    	
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
	
	public function rerunResize($object, $property, $dir, $key)
	{
		return $this->_resize($object, $property, $dir, $key);
	}
	
	public function getAll($args)
	{
		return $this->fetchAll($args);
	}
}
