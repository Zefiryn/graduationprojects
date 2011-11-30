<?php

class Application_Model_DbTable_Faqs extends Zefir_Application_Model_DbTable
{

	protected $_raw_name = 'faq';
	protected $_name;
	protected $_primary = 'faq_id';

	/**
	 * An array of child tables information
	 * @var array
	 */
	protected $_belongsTo = array(
    	'lang' => array(
    		'model' => 'Application_Model_Lang',
			'column' => 'lang_id',
			'refColumn' => 'lang_id'
	)
	);


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

	public function findLastQuestion($lang)
	{
		$select = $this->select()
		->where('lang_id = ?', $lang)
		->order('position DESC')
		->limit(1);
		return $this->fetchAll($select)->current();
	}

	public function getStringLength($lang_id)
	{
		$lang = new Application_Model_Languages();

		$sql = 'SELECT SUM(CHAR_LENGTH(faq_question) + CHAR_LENGTH(faq_answer)) AS StrLength FROM '.$this->_name.' WHERE lang_id = ?';

		$s = $this->getAdapter()->query($sql, array($lang->findLangId($lang_id)));

		while ($row = $s->fetch()) {
			return ($row['StrLength']);
		}
	}


}

