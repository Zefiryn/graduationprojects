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
			'order' => array('surname', 'name')
		)
	);
	
	
}

