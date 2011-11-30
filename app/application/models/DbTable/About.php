<?php

class Application_Model_DbTable_About extends Zefir_Application_Model_DbTable
{

	protected $_raw_name = 'about';
	protected $_primary = 'about_id';


	protected $_belongsTo = array(

    	'lang' => array(
    		'model' => 'Application_Model_Languages',
    		'column' => 'lang_id',
			'refColumn' => 'lang_id'
	));

	public function __construct($config = array())
	{
		parent::__construct(array());
	}

	public function search($property, $value, $like = FALSE)
	{
		if (!$like)
		$select = $this->select()->where($property.' = ?', $value);
		else
		$select = $this->select()->where($property.' LIKE ?', '%'.$value.'%');

		return $this->fetchAll($select);
	}


}

