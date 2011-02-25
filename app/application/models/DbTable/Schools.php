<?php

class Application_Model_DbTable_Schools extends Zefir_Application_Model_DbTable
{

    protected $_raw_name = 'schools';
	protected $_name = '';
    protected $_primary = 'school_id';

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
}

