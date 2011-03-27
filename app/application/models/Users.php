<?php

class Application_Model_Users extends GP_Application_Model
{
	protected $_user_id;
	protected $_nick;
	protected $_password;
	protected $_name;
	protected $_surname;
	protected $_address;
	protected $_phone;
	protected $_email;
	protected $_show_email;
	protected $_role;
	protected $_applications;
	
	protected $_dbTableModelName = 'Application_Model_DbTable_Users';
	
	protected $_set_vars = array('_user_id', '_nick', '_password', '_name', '_surname', 
								'_address', '_phone', '_email', '_show_email', '_role',
								'_applications');
	protected $_get_vars = array('_user_id', '_nick', '_password', '_name', '_surname', 
								'_address', '_phone', '_email', '_show_email', '_role',
								'_applications');
	
	
	public function __construct($id = null, array $options = null) 
	{
	    return parent::__construct($id, $options);
	}
	
	public function populateFromForm($data)
	{
		parent::populateFromForm($data);
		
		if ($this->_role == null && $this->_user_id == null)
			$this->_role = 'user';
			
		return $this;
	}
	
	/**
	 * Convert user's name into url safe string
	 * 
	 * @access public
	 * @return string
	 */
	public function getUserUrlName()
	{
		return Zefir_Filter::strToUrl($this->_name.'_'.$this->_surname);
	}
	
	/**
	 * Get user data
	 *
	 * @access public
	 * @param int|string $id
	 * @param boolean $safe
	 * @return Application_Model_Users
	 */
	public function getUser($id, $safe = FALSE)
	{
		$this->getDbTable()->findUser($id, $this);

		if ($safe)
		{
			$this->_password = null;
		}
		return $this;
	}
	
	/**
	 * Get user name and surname or nick if the previous is not present
	 * 
	 * @access public
	 * @return string $name
	 */
	public function getUserFullName()
	{
		if ($this->_name != null || $this->_surname != null)
			$name = $this->_name.' '.$this->_surname;
		else
			$name = $this->_nick;
			
		return $name;
	}
	
	public function getUsers($order = 'role')
	{
		$select = $this->getDbTable()->select()->order(array($order, 'surname', 'name'));
		$rowset = $this->getDbTable()->fetchAll($select);
		
		$users = array();
		foreach($rowset as $row)
		{
			$user = new $this;
			$users[] = $user->populateWithReference($row);
		}
		
		return $users;
	}
	
	public function prepareFormArray()
	{
		$data = array(
			'user_id' => $this->_user_id,
			'name' => $this->_name,
			'surname' => $this->_surname,
			'nick' => $this->_nick,
			'phone' => $this->_phone,
			'address' => $this->_address,
			'email' => $this->_email,
			'show_email' => $this->_show_email,
			'role' => $this->_role
		);
		
		return $data;
	}	
}


