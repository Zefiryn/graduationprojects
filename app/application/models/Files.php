<?php

class Application_Model_Files extends GP_Application_Model
{
	protected $_file_id;
	protected $_application;
	protected $_path;
	protected $_file_desc;
	
	protected $_dbTableModelName = 'Application_Model_DbTable_Files';
	
	protected $_set_vars = array('_file_id', '_application', '_path', '_file_desc');
	protected $_get_vars = array('_file_id', '_application', '_path', '_file_desc');
	
	
	public function __construct(array $options = null) 
	{
	    parent::__construct($options);
	}
	
	public function save()
	{
		return $this->getDbTable()->save($this);
	}

	public function resize($orientation, $size)
	{
		$path = APPLICATION_PATH.'/../public/assets/applications/'.$this->_path;
		
		if (is_file($path))
		{
			$file_size = getimagesize($path);
			$width = $file_size[0];
			$height = $file_size[1];
			
			if ($orientation == 'width')
			{
				$ratio = $size/$width;
				$width = $size;
				$height = $ratio*$height;
			} 
			else
			{
				$ratio = $size/$height;
				$height = $size;
				$width = $ratio*$width;
			}
		}
		
		return 'width="'.$width.'" height="'.$height.'"';
	}
}

