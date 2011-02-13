<?php

class Application_Model_DbTable_Applications extends Zefir_Application_Model_DbTable
{

	/**
	 * Name of the table without prefix
	 * @var string
	 */
    protected $_raw_name = 'applications';
    
     /**
     * Name of the table generated by the constructor
     * @var string
     */
    protected $_name = '';
    
    /**
	 * Primary key of the table
	 * @var string
	 */
    protected $_primary = 'application_id';


    /**
     * An array of child tables information
     * @var array
     */
    protected $_referenceMap = array(
		'Editions' => array(
    		'objProperty' => '_edition',
			'columns' => array('edition_id'),
			'refTableClass' => 'Application_Model_DbTable_Editions',
			'refColumns' => array('edition_id'),
			'onDelete' => self::CASCADE,
			'onUpdate' => self::RESTRICT
		),
		'Users' => array(
    		'objProperty' => '_user',
			'columns' => array('user_id'),
			'refTableClass' => 'Application_Model_DbTable_Users',
			'refColumns' => array('user_id'),
			'onDelete' => self::CASCADE,
			'onUpdate' => self::RESTRICT
		),
		'Schools' => array(
    		'objProperty' => '_school',
			'columns' => array('school_id'),
			'refTableClass' => 'Application_Model_DbTable_Schools',
			'refColumns' => array('school_id'),
			'onDelete' => self::CASCADE,
			'onUpdate' => self::RESTRICT
		),
		'Degrees' => array(
    		'objProperty' => '_degree',
			'columns' => array('degree_id'),
			'refTableClass' => 'Application_Model_DbTable_Degrees',
			'refColumns' => array('degree_id'),
			'onDelete' => self::CASCADE,
			'onUpdate' => self::RESTRICT
		),
		'WorkTypes' => array(
    		'objProperty' => '_work_type',
			'columns' => array('work_type_id'),
			'refTableClass' => 'Application_Model_DbTable_WorkTypes',
			'refColumns' => array('work_type_id'),
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

