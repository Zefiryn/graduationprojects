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
    protected $_belongsTo = array(
    	'lang' => array(
    		'model' => 'Application_Model_Lang',
			'column' => 'lang_id',
			'refColumn' => 'lang_id'
		)
	);
	
	/**
	 * Get regulation for a given edition and language
	 * 
	 * @access public
	 * @param string $lang
	 * @param int $edition
	 * @return Zend_Db_Table_Rowset
	 */
	public function getRegulations($lang)
	{
		$select = $this->select()
				->where('lang_id = ?', $lang)
				->order('paragraph_no');
		return $this->fetchAll($select);
	}	
}

