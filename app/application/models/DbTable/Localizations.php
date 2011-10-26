<?php

class Application_Model_DbTable_Localizations extends Zefir_Application_Model_DbTable
{

    protected $_raw_name = 'localizations';
	protected $_name;
	protected $_primary = 'item_id';
	
	 protected $_belongsTo = array(
    	'language' => array(
    		'model' => 'Application_Model_Languages',
    		'column' => 'lang_id',
			'refColumn' => 'lang_id'
       	),
       	'caption' => array(
    		'model' => 'Application_Model_Captions',
    		'column' => 'caption_id',
			'refColumn' => 'caption_id'
       	)
	);

	public function isLocalization($lang)
	{
		$languages = new Application_Model_Languages();
		return $languages->isLocalization($lang);
	}
	
	public function save(Application_Model_Localizations $localization)
	{
		$row = $this->_findRow($localization);
		if ($row)
		{
			$localization->item_id = $row['item_id'];
		}
		
		parent::save($localization);
	}
	
	protected function _findRow($object)
	{
		$select = $this->select()->where('caption_id = ?', $object->caption_id)->where('lang_id = ?', $object->lang_id);
		return $this->fetchRow($select);
	}
}

