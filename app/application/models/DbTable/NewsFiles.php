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

	public function save(Application_Model_NewsFiles $file)
	{
		if ($file->news_file_id != null)
    		$oldData = new Application_Model_DiplomaFiles($file->news_file_id);
    	else 
    		$oldData = null;
		
		$row = $this->find($file->news_file_id)->current();
		
		if (!$row)
		{
			$row = $this->createRow();
		}
		
		//trim /assets/cache or assets/images
		if (strstr($file->path, 'assets'))
		{
			if (strstr($file->path, 'cache'))
				$file->path = substr($file->path, strpos($file->path, '/cache') + strlen('/cache/'));
		}
		$options = Zend_Registry::get('options');
		$file = $this->_copyFile($file, 'path', $options['upload']['images'], 'news_image', $oldData);

			
		$row->news_id = $file->news_id;
		$row->path = $file->path;
		$row->main_image = $file->main_image;
		
		if ($row->save())
    	{
    		if ($file->news_file_id == null)
    			$file->news_file_id = $this->getAdapter()->lastInsertId();
    	}
    	else 
    	{
    		throw new Zend_Exception('Couldn\'t save data');
    	}
    	
    	
    	return $file;
	}
	

}