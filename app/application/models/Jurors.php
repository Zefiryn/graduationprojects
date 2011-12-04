<?php
class Application_Model_Jurors extends GP_Application_Model
{
	public $juror_id;
	public $user_id;
	public $juror_name;
	public $country;
	public $wage;
	protected $users;
	
	protected $_dbTableModelName = 'Application_Model_DbTable_Jurors';
	
	public function __construct($id = null, array $options = null)
	{
		return parent::__construct($id, $options);
	}
}