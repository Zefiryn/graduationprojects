<?php

class Application_Model_DbTable_Partners extends Zefir_Application_Model_DbTable
{
	protected $_raw_name = 'partners';
	
	protected $_primary = 'partner_id';
	
	public function __construct($config = array())
	{
		parent::__construct($config);
	}
	
	public function save(Application_Model_Partners $partner)
	{
		if ($partner->partner_id != null)
			$oldData = new Application_Model_Partners($partner->partner_id);
		else
			$oldData = null;
	
		if (strstr($partner->partner_file, 'cache')) {
				
			$this->_saveFile($partner);
			$partner->resizeImage();
			$removeOld = true;
		}
		else
		{
			$partner->partner_file = $oldData->partner_file;
			$removeOld = false;
		}
		parent::save($partner);
	
		if ($removeOld == true && $oldData != null)
		{
			$oldData->removeImage();
		}
			
		return $partner;
			
	}
	
	protected function _saveFile($partner) {
		$options = Zend_Registry::get('options');
		$name = substr($partner->partner_file, strpos($partner->partner_file, '/') + 1);
		$fileName = 'partner.'.strtolower(Zefir_Filter::getExtension($name));
		$fileName = $this->_getNewName(APPLICATION_PATH.'/../public'.$options['upload']['partners'].'/', $fileName);
		if ($this->_copy($name, $options['upload']['partners'].'/'.$fileName))
		{
			$partner->partner_file = $fileName;
		}
		else {
			$partner->partner_file = null;
		}
	
		return $partner;
	}
}
