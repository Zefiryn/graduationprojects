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
}

