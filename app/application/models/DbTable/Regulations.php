<?php

class Application_Model_DbTable_Regulations extends Zefir_Application_Model_DbTable
{

    protected $_raw_name = 'regulations';
	protected $_name;
	protected $_primary = 'paragraph_id';

	/**
     * An array of child tables information
     * @var array
     */
    protected $_referenceMap = array(
    	'Editions' => array(
    		'objProperty' => '_edition',
			'columns' => array('edition_id'),
			'refTableClass' => 'Application_Model_DbTable_Editions',
			'refColumns' => array('edition_id'),
			'onDelete' => self::CASCADE,
			'onUpdate' => self::RESTRICT
		)
	);
	
	/**
	 * An array of parent table information
	 * @var array
	 */
	protected $_dependentTables = array();
	
	public function getRegulations($lang, $edition)
	{
		$select = $this->select()
				->where('regulation_lang = ?', $lang)
				->where('edition_id = ? ', $edition)
				->order('paragraph_no');
		return $this->fetchAll($select);
	}
	
	
}

