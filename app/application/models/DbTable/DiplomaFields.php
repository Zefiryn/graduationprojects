<?php

class Application_Model_DbTable_DiplomaFields extends Zefir_Application_Model_DbTable
{

	/**
	 * Name of the table without prefix
	 * @var string
	 */
	protected $_raw_name = 'diploma_fields';

	/**
	 * Name of the table generated by the constructor
	 * @var string
	 */
	protected $_name = '';

	/**
	 * Primary key of the table
	 * @var string
	 */
	protected $_primary = 'diploma_field_id';


	/**
	 * An array of child tables information
	 * @var array
	 */
	protected $_belongsTo = array(

    	'diploma' => array(
    		'model' => 'Application_Model_Results',
    		'column' => 'diploma_id',
			'refColumn' => 'diploma_id'
	),

       	'lang' => array(
       		'model' => 'Application_Model_Languages',
			'column' => 'lang_id',
			'refColumn' => 'lang_id'
	),

		'field' => array(
       		'model' => 'Application_Model_Fields',
			'column' => 'field_id',
			'refColumn' => 'field_id'
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

	/**
	 * Save or update diploma field data in the database
	 *
	 * @param Application_Model_DiplomaFields $field
	 * @throws Zend_Exception
	 * @return Application_Model_DiplomaFields $field
	 */
	public function save(Application_Model_DiplomaFields $field)
	{
		$select = $this->select()
					->where('diploma_id = ?', $field->diploma_id)
					->where('lang_id = ?', $field->lang_id)
					->where('field_id = ?', $field->field_id);
		$row = $this->fetchRow($select);

		if ($row)
			$field->diploma_field_id = $row['diploma_field_id'];

		parent::save($field);
	}


}

