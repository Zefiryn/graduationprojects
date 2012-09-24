<?php

class Application_Model_Partners extends GP_Application_Model
{
	public $partner_id;
	public $partner_name;
	public $partner_link;
	public $partner_type;
	public $partner_file;
	protected $_partnersCollection;
	
	protected $_image = array(
			'property' => 'partner_file',
			'dir' => '/assets/partners'
	);
	protected $_imageData = array(
			'small' => array(
					'width' => 200,
					'height' => 85,
					'crop' => false,
					'ratio' => 'height'	//save ratio according to new width
			)
	);
	
	protected $_dbTableModelName = 'Application_Model_DbTable_Partners';

	public function __construct($id = null, array $options = null)
	{
		return parent::__construct($id, $options);
	}

	public function populateFromForm($array) 
	{	
		parent::populateFromForm($array);
		if (isset($array['partner_fileCache'])) $this->partner_file = $array['partner_fileCache'];
		return $this;
	}	
	
	public function delete() 
	{
		$this->removeImage();
		parent::delete();
	}
	
	public function getLink() 
	{
		$link = $this->partner_link;
		if (!strstr($link, 'http')) {
			$link = 'http://'.$link;
		}
		return $link;
	}
	
	public function getByType($type) 
	{
		if ($this->_partnersCollection['base'] == null) 
		{
			$this->_partnersCollection['base'] = $this->fetchAll();
		}
		
		if (!isset($this->_partnersCollection[$type])) 
		{
			$this->_partnersCollection[$type] = array();
			foreach ($this->_partnersCollection['base'] as $partner) 
			{
				if ($partner->partner_type == $type) 
				{
					$this->_partnersCollection[$type][] = $partner;
				}
			}
		}
		
		return $this->_partnersCollection[$type];
	}
}

