<?php

class Application_Model_DbTable_NewsDetails extends Zefir_Application_Model_DbTable
{

    protected $_raw_name = 'news_details';
    protected $_name;
	protected $_primary = 'news_detail_id';

	protected $_belongsTo = array(
    	'news' => array(
    		'model' => 'Application_Model_News',
    		'column' => 'news_id',
			'refColumn' => 'news_id'
       	),
       	'lang' => array(
    		'model' => 'Application_Model_Languages',
    		'column' => 'lang_id',
			'refColumn' => 'lang_id'
       	),
	);


}

