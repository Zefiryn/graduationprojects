<?php

class Application_Model_DbTable_Faqs extends Zefir_Application_Model_DbTable
{

	protected $_raw_name = 'faq';
	protected $_name;
	protected $_primary = 'faq_id';

	
	public function getFaq($lang)
	{
		$select = $this->select()->where('faq_lang = ?', $lang);
		return $this->fetchAll($select);
	}
	
	public function deleteFaq($lang)
	{
		$where = $this->select()->where('faq_lang = ?', $lang);
		$this->fetchAll($where)->delete();
	}


}

