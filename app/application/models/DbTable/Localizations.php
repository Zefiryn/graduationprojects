<?php

class Application_Model_DbTable_Localizations extends Zefir_Application_Model_DbTable
{

    protected $_raw_name = 'localizations';
	protected $_name;
	protected $_primary = 'item_id';

	/**
     * An array of child tables information
     * @var array
     */
    protected $_referenceMap = array();
	
	/**
	 * An array of parent table information
	 * @var array
	 */
	protected $_dependentTables = array();
	
	public function isLocalization($lang)
	{
		$select = $this->select()->where('lang_code = ?', $lang);
		return $this->fetchAll($select)->count();
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

