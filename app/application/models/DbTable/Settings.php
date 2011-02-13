<?php

class Application_Model_DbTable_Settings extends Zefir_Application_Model_DbTable
{
	/**
	 * Name of the table without prefix
	 * @var string
	 */
    protected $_raw_name = 'settings';
        
     /**
     * Name of the table generated by the constructor
     * @var string
     */
	protected $_name = '';
    
    /**
	 * Primary key of the table
	 * @var string
	 */
    protected $_primary = 'current_edition';

    /**
     * An array of child tables information
     * @var array
     */
    protected $_referenceMap = array(
		'Editions' => array(
    		'objProperty' => '_current_edition',
			'columns' => array('current_edition'),
			'refTableClass' => 'Application_Model_DbTable_Editions',
			'refColumns' => array('edition_id'),
			'onDelete' => self::CASCADE,
			'onUpdate' => self::RESTRICT
		),
		'Users' => array(
    		'objProperty' => '_template_default',
			'columns' => array('template_default'),
			'refTableClass' => 'Application_Model_DbTable_TemplateSettings',
			'refColumns' => array('template_id'),
			'onDelete' => self::CASCADE,
			'onUpdate' => self::RESTRICT
		)
	);
	
	/**
	 * An array of parent table information
	 * @var array
	 */
	protected $_dependentTables = array(
		'_files' => 'Application_Model_DbTable_Files',
	);
	
	/**
	 * constructor
	 * @access public
	 * @param array $config
	 * @return void
	 */
    public function __construct($config = array())
    {
      parent::__construct(array());
    }

}

