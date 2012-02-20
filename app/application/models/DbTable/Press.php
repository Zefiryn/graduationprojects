<?php

class Application_Model_DbTable_Press extends Zefir_Application_Model_DbTable
{

	protected $_raw_name = 'press';
	protected $_name;
	protected $_primary = 'element_id';

	/**
	 * An array of child tables information
	 * @var array
	 */
	protected $_belongsTo = array(
    	'edition' => array(
    		'model' => 'Application_Model_Edition',
			'column' => 'edition_id',
			'refColumn' => 'edition_id'
	)
	);
	
	public function getAllAsType($edition)
	{
		$select = $this->select()->where('edition_id = ?', $edition)->order('element_type ASC');
		return $this->fetchAll($select);
	}

}

