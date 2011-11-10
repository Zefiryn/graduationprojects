<?php

class Application_Model_DbTable_Languages extends Zefir_Application_Model_DbTable
{

    protected $_raw_name = 'languages';
	protected $_name;
	protected $_primary = 'lang_id';
	
	protected $_hasMany = array(
		'localizations' => array(
			'model' => 'Application_Model_Localizations',
			'refColumn' => 'lang_id',
		),
		'news' => array(
			'model' => 'Application_Model_News',
			'refColumn' => 'lang_id',
		),
		'about' => array(
			'model' => 'Application_Model_About',
			'refColumn' => 'lang_id',
		),
		'regulations' => array(
			'model' => 'Application_Model_Regualtions',
			'refColumn' => 'lang_id',
			'order' => 'paragraph_no'
		),
		'faq' => array(
			'model' => 'Application_Model_Faqs',
			'refColumn' => 'lang_id',
			'order' => 'position'
		),
	);

	public function isLocalization($lang)
	{
		$select = $this->select()->where('lang_code = ?', $lang);
		return $this->fetchAll($select)->count();
	}
	
	public function findLang($lang)
	{
		$select = $this->select()->where('lang_code = ?', $lang);
		
		$row = $this->fetchRow($select);
		
		return $row;
	}
	
}

