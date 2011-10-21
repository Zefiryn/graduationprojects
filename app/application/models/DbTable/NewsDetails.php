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
	
	public function save(Zefir_Application_Model $detail)
	{
		$find = $this->select()->where('news_id = ?', $detail->news_id)->where('lang_id = ?', $detail->lang_id);
		$row = $this->fetchRow($find);
		
		if (!$row)
		{
			$this->createRow();
		}
		
		$row->news_id = $detail->news_id;
		$row->lang_id = $detail->lang_id;
		$row->news_title = $detail->news_title;
		$row->news_lead = $detail->news_lead;
		$row->news_text = $detail->news_text;
		
		if ($row->save())
    	{
    		if ($detail->news_detail_id == null)
    			$detail->news_detail_id = $this->getAdapter()->lastInsertId();
    	}
    	else 
    	{
    		throw new Zend_Exception('Couldn\'t save data');
    	}
    	
    	return $detail;
	}


}

