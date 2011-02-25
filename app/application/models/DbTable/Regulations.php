<?php

class Application_Model_DbTable_Regulations extends Zefir_Application_Model_DbTable
{

    protected $_raw_name = 'regulations';
	protected $_name;
	protected $_primary = 'paragraph_id';

	/**
     * An array of child tables information
     * @var array
     */
    protected $_referenceMap = array(
    	'Editions' => array(
    		'objProperty' => '_edition',
			'columns' => array('edition_id'),
			'refTableClass' => 'Application_Model_DbTable_Editions',
			'refColumns' => array('edition_id'),
			'onDelete' => self::CASCADE,
			'onUpdate' => self::RESTRICT
		)
	);
	
	/**
	 * An array of parent table information
	 * @var array
	 */
	protected $_dependentTables = array();
	
	public function getRegulations($lang, $edition)
	{
		$select = $this->select()
				->where('regulation_lang = ?', $lang)
				->where('edition_id = ? ', $edition)
				->order('paragraph_no');
		return $this->fetchAll($select);
	}
	
	public function saveParagraph($data)
	{
		$id = $data->_paragraph_id;
		if ($id)
			$row = $this->find($id)->current();

		if (!$row || $id == NULL)
			$row = $this->createRow();
		
		$row->paragraph_id = $data->_paragraph_id;
		$row->edition_id = $data->_edition;
		$row->regulation_lang = $data->_regulation_lang;
		$row->paragraph_no = $data->_paragraph_no;
		$row->paragraph_text = $data->_paragraph_text;
		
		if ($row->save())
		{
			if ($id == NULL)
				$data->_paragraph_id = $this->getAdapter()->lastInsertId();
		}
		else
			throw new Zend_Exception('Unable to save data');
		
		return $data;
	}
	
	public function deleteParagraph($data)
	{
	}
}

