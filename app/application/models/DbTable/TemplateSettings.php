<?php

class Application_Model_DbTable_TemplateSettings extends Zefir_Application_Model_DbTable
{

    protected $_raw_name = 'template_settings';
    protected $_primary = 'template_id'; 
    protected $_name;


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

