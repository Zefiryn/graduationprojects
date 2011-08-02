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
		)
	);

	public function isLocalization($lang)
	{
		$select = $this->select()->where('lang_code = ?', $lang);
		return $this->fetchAll($select)->count();
	}
	
}

