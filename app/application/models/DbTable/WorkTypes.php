<?php

class Application_Model_DbTable_WorkTypes extends Zefir_Application_Model_DbTable
{

    protected $_raw_name = 'work_types';
	protected $_name = '';
    protected $_primary = 'work_type_id';

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

