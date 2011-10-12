<?php

class Application_Model_DbTable_NewsFiles extends Zefir_Application_Model_DbTable
{

    protected $_raw_name = 'news_files';
    protected $_name;
	protected $_primary = 'news_file_id';

	protected $_belongsTo = array(
    	'news' => array(
    		'model' => 'Application_Model_News',
    		'column' => 'news_id',
			'refColumn' => 'news_id'
       	)
	);


}