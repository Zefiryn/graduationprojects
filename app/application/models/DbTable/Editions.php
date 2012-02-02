<?php

class Application_Model_DbTable_Editions extends Zefir_Application_Model_DbTable
{

	protected $_raw_name = 'editions';
	protected $_name = '';
	protected $_primary = 'edition_id';

	/**
	 * An array of parent table information
	 * @var array
	 */
	protected $_hasMany = array(
		'applications' => array(
			'model' => 'Application_Model_Applications',
			'refColumn' => 'edition_id'
	),
		'diplomas' => array(
			'model' => 'Application_Model_Diplomas',
			'refColumn' => 'edition_id',
			'order' => array('surname ASC', 'name ASC')
	)
	);

	public function resetPublications()
	{
		$sql = 'UPDATE ' . $this->getTableName() . ' SET publish_results = 0';
		return $this->getAdapter()->query($sql);
	}
	
	public function findPublicEdition()
	{
		$select = $this->select()->where('publish_results = 1')->order('edition_name DESC')->limit(1);
		
		return $this->fetchRow($select);
	}

}

