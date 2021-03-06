<?php

class Application_Model_News extends GP_Application_Model
{
	public $news_id;
	public $added;
	public $published;
	public $link;
	protected $details;
	protected $files;

	protected $_dbTableModelName = 'Application_Model_DbTable_News';


	public function __construct($id = null, array $options = null)
	{
		return parent::__construct($id, $options);
	}

	public function getPagination($role = 'user')
	{
		$tplSettings = Zend_Registry::get('tplSettings');
		if ($role != 'admin')
		{
			$rows = $this->getDbTable()->getRowsNum(array('published = ?' => 1));
			$limit = $tplSettings->news_limit;
		}
		else 
		{
			$rows = $this->getDbTable()->getRowsNum();
			$limit = $tplSettings->news_limit - 1;
		}
		$pages = (int)ceil($rows/$limit);

		return $pages;
	}
	
	public function getPage($role = 'user')
	{
		$unpublished = $role == 'admin' ? true : false;
		$rowset = $this->getDbTable()->getAllInOrder('added DESC', $unpublished);
		
		$tplSettings = Zend_Registry::get('tplSettings');
		$limit = $role == 'admin' ? $tplSettings->news_limit - 1 : $tplSettings->news_limit;
		foreach($rowset as $i => $row)
		{
			if ($row['news_id'] == $this->news_id)
			{
				return ceil(++$i/$limit);
			}
		}
	}

	public function getDetail($property, $lang)
	{
		if ($this->details == null)
		$this->__get('details');

		$options = Zend_Registry::get('options');
		$default_language = $options['i18n']['default_language'];

		foreach($this->details as $text)
		{
			if ($text->lang->lang_code == $lang)
			$detail = $text->$property;

			if ($text->lang->lang_code == $default_language)
			$detail_def_lang = $text->$property;
		}

		if (isset($detail) && $detail != '')
		return  $detail;
		elseif (isset($detail_def_lang) && $detail_def_lang != '')
		return $detail_def_lang;
		else
		return null;

	}

	public function getImage($key)
	{
		if ($this->files == null)
		$this->__get('files');
			
		if (count($this->files) > 0)
		{
			return $this->files[0]->getImage($key);
		}

		else
		return NULL;
	}

	public function hasPhoto()
	{
		if ($this->files == null)
		$this->__get('files');
			
		if (count($this->files) > 0)
		{
			$options = Zend_Registry::get('options');
			if (file_exists(APPLICATION_PATH.'/../public'.$options['upload']['images'].'/'.$this->files[0]->path))
			return TRUE;
			else
			return FALSE;
		}
		else
		return FALSE;
	}

	public function prepareFormarray()
	{
		$data = array(
			'news_id' => $this->news_id,
			'link' => $this->link,
			'added' => date('d-m-Y', $this->added),
			'published' => $this->published
		);
		$languages = new Application_Model_Languages();
		foreach($languages->fetchAll() as $lang)
		{
			$data[$lang->lang_code] = array(
				'news_title' => $this->getDetail('news_title', $lang->lang_code),
				'news_lead' => $this->getDetail('news_lead', $lang->lang_code),
				'news_text' => $this->getDetail('news_text', $lang->lang_code),
			);
		}
		return $data;
	}

	public function populateFromForm($values)
	{
		$values['added'] = $values['added'] != null ? strtotime($values['added']) : time();
		$files = $values['files'];
		unset($values['files']);
				
		parent::populateFromForm($values);

		$languages = new Application_Model_Languages();

		$this->details = array();
		foreach($languages->fetchAll() as $lang)
		{
			$detail = new Application_Model_NewsDetails();
			$detail->populateFromForm($values[$lang->lang_code]);
			if (!$detail->isEmpty())
			$this->details[] = $detail;
		}

		$log = Zend_Registry::get('log');
		
		$imgOrder = 1;
		$t = var_dump($this, true);
		
		if ($values['news_id'] != null) 
		{
			$this->__get('files');
			$lastFile = count($this->files);
			$imgOrder = $this->files[$lastFile-1]->position + 1;
		}
		$this->files = array();
		
		foreach(explode(',', $files) as $i => $file)
		{
			if (trim($file))
			{
				$newsFile = new Application_Model_NewsFiles();
				$newsFile->news_id = $this->news_id;
				$newsFile->path = trim($file);
				$newsFile->position = $imgOrder++;
				
				$this->files[] = $newsFile;
			}
		}

		return $this;
	}

	public function save()
	{
		parent::save();

		foreach ($this->details as $detail)
		{
			$detail->news_id = $this->news_id;
			$detail->save();
		}
		foreach ($this->files as $file)
		{
			$file->news_id = $this->news_id;
			$file->save();
		}

		return $this;
	}
}

