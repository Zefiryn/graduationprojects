<?php

class Application_Model_DbTable_News extends Zefir_Application_Model_DbTable
{

	protected $_raw_name = 'news';
	protected $_name;
	protected $_primary = 'news_id';

	protected $_hasMany = array(
		'details' => array(
			'model' => 'Application_Model_NewsDetails',
			'refColumn' => 'news_id',
		),
		'files' => array(
			'model' => 'Application_Model_NewsFiles',
			'refColumn' => 'news_id',
		),
	);
	
	public function getAll($page)
	{
		$select = $this->select()->order('added DESC');

		if ($page)
		{
			$tplSettings = Zend_Registry::get('tplSettings');
			$limit = $page * $tplSettings->news_limit;
			$offset = ($page - 1) * $tplSettings->news_limit; 
			$select->limit($limit, $offset);
		}
		
		return $this->fetchAll($select);
	}

}

