<?php

class Application_Model_DbTable_Users extends Zefir_Application_Model_DbTable
{

    protected $_raw_name = 'users';
	protected $_name = '';
    protected $_primary = 'user_id';

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
		'_applications' => 'Application_Model_DbTable_Applications');
}

