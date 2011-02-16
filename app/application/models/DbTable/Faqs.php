<?php

class Application_Model_DbTable_Faqs extends Zefir_Application_Model_DbTable
{

	protected $_raw_name = 'faq';
	protected $_name;
	protected $_primary = 'faq_id';

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
	
	public function getFaq($lang)
	{
		$select = $this->select()->where('faq_lang = ?', $lang);
		return $this->fetchAll($select);
	}


}

