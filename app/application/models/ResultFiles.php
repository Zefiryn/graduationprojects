<?php

class Application_Model_Files extends GP_Application_Model
{
	public $file_id;
	public $path;
	public $file_desc;
	protected $result;
	
	protected $_dbTableModelName = 'Application_Model_DbTable_ResultFiles';
	
	public function __construct($id = null, array $options = null) 
	{
	    return parent::__construct($id, $options);
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

