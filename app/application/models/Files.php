<?php

class Application_Model_Files extends GP_Application_Model
{
	public $file_id;
	public $application_id;
	public $path;
	protected $application;
	protected $_image = array(
		'property' => 'path',
		'dir' => '/assets/editions'
	);
	protected $_imageData = array(
		'thumb' => array(
			'width' => 220,
			'height' => 210,
			'crop' => false,
			'ratio' => 'width' //save ratio according to new width
		),
		'small' => array(
			'width' => 470,
			'height' => 260,
			'crop' => false,
			'ratio' => 'width'	//save ratio according to new width
		)
	);
	
	protected $_dbTableModelName = 'Application_Model_DbTable_Files';
	
	
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

