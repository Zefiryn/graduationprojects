<?php

class Application_Model_DbTable_Captions extends Zefir_Application_Model_DbTable
{

	protected $_raw_name = 'captions';
	protected $_name;
	protected $_primary = 'caption_id';

	protected $_hasMany = array(
		'localizations' => array(
			'model' => 'Application_Model_Localizations',
			'refColumn' => 'caption_id',
	)
	);

	public function findCaption($name)
	{
		$select = $this->select()->where('name = ?', $name);
		$row = $this->fetchRow($select);

		return $row;
	}
}

