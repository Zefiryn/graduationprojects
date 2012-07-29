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
	protected $_belongsTo = array(
		'edition' => array(
    		'model' => 'Application_Model_Editions',
			'column' => 'edition_id',
			'refColumn' => 'current_edition'
	),
		'template' => array(
    		'model' => 'Application_Model_TemplateSettings',
			'column' => 'template_id',
			'refColumn' => 'template_default'
	)
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

	public function getSettings(Application_Model_Settings $settings)
	{
		$select = $this->select()->order('current_edition DESC')->limit('1');
		$row = $this->fetchRow($select);
		$settings->populate($row);
		 
		return $settings;
	}


	public function save(Application_Model_Settings $settings)
	{
		
		$row = $this->fetchAll()->current();
		
		$columns = $row->toArray();
		foreach($columns as $name => $value)
		{
			$row->$name = $settings->$name;
		}
		
		if (!$row->save())
		{
			throw new Zend_Exception('Couldn\'t save data');
		}
		
		return $object;
	}

}

