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

	public function save(Zefir_Application_Model $file)
	{
		$find = $this->select()->where('news_id = ?', $file->news_id);
		$row = $this->fetchRow($find);
		
		if (!$row)
		{
			$this->createRow();
		}
		
		$row->news_id = $file->news_id;
		$row->path = $file->path;
		$row->main_image = $detail->main_image;
		
		if ($row->save())
    	{
    		if ($id == null)
    			$detail->$primary = $id = $this->getAdapter()->lastInsertId();
    	}
    	else 
    	{
    		throw new Zend_Exception('Couldn\'t save data');
    	}
    	
    	return $detail;
	}

}