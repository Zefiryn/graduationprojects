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
			'order' => 'main_image DESC',
	),
	);

	public function getAll($args)
	{
		$select = $this->select()->order('added DESC');

		if ($args[1])
			$select = $select->where('published = 1');
		
		if ($args[0])
		{
			$tplSettings = Zend_Registry::get('tplSettings');
			$limit = $args[0] * $tplSettings->news_limit;
			$offset = ($args[0] - 1) * $tplSettings->news_limit;
			$select->limit($limit, $offset);
		}

		return $this->fetchAll($select);
	}

}

