<?php

class Application_Model_TemplateSettings extends GP_Application_Model
{ 
	protected $_dbTableModelName = 'Application_Model_DbTable_TemplateSettings';
	protected $_set_vars = array('_template_name', '_template_full_name', 
								'_book_photo_width', '_writer_photo_width');
	protected $_get_vars = array('_template_name', '_template_full_name', 
								'_book_photo_width', '_writer_photo_width');
	
	public function __construct(array $options = null) 
	{
	    parent::__construct($options);
	}
}

