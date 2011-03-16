<?php

class Application_Model_DbTable_Editions extends Zefir_Application_Model_DbTable
{

    protected $_raw_name = 'editions';
    protected $_name = '';
    protected $_primary = 'edition_id';

	/**
     * An array of child tables information
     * @var array
     */
    protected $_referenceMap = array();
	
	/**
	 * An array of parent table information
	 * @var array
	 */
	protected $_dependentTables = array(
		'_applications' => 'Application_Model_DbTable_Applications',
	);
	
	
}

