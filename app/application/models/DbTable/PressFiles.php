<?php

class Application_Model_DbTable_PressFiles extends Zefir_Application_Model_DbTable
{

	protected $_raw_name = 'press_files';
	protected $_name;
	protected $_primary = 'press_file_id';

	protected $_belongsTo = array(
			'press' => array(
					'model' => 'Application_Model_Press',
					'column' => 'element_id',
					'refColumn' => 'element_id'
			)
	);

}

