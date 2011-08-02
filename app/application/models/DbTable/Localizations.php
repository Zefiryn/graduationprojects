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
	
	public function save(Application_Model_Localizations $record)
	{
		$row = $this->getRowByName($record);
		
		if (!$row)
			$row->createRow();

		$row->name = $record->_name;
		$row->lang_code = $record->_lang_code;
		$row->text = $record->_text;

		if ($row->save())
		{
			if ($row->item_id == null)
			{
				$record->_item_id = $this->getAdapter()->lastInsertId();
			}
			else 
				$record->_item_id = $row->item_id;

		}
		else
			throw new Zend_Exception('Couldn\'t save record');
	
		return $record;
	}
	
	public function getRowByName($record)
	{
		$select = $this->select()->where('name = ?', $record->_name)->where('lang_code = ?', $record->_lang_code);
		return $this->fetchRow($select);
	}
}

