<?php

class Application_Model_Users extends GP_Application_Model
{
	public $user_id;
	public $nick;
	protected  $_password;
	public $name;
	public $surname;
	public $address;
	public $phone;
	public $email;
	public $show_email;
	public $juror_id;
	protected $_role;
	protected $applications;
	protected $juror;
	protected $disputes;

	protected $_dbTableModelName = 'Application_Model_DbTable_Users';

	public function __construct($id = null, array $options = null)
	{
		return parent::__construct($id, $options);
	}

	public function __get($var)
	{
		if ($var == '_role' || $var == 'role')
		return $this->_role;
		else
		return parent::__get($var);
	}

	public function populate($row)
	{
		parent::populate($row);

		if (is_array($row))
		{
			$this->_role = $row['role'];
		}
		else 
		{
			$this->_role = $row->role;
		}
		return $this;
	}

	public function populateFromForm($data)
	{
		parent::populateFromForm($data);

		if ($this->_role == null && $this->user_id == null)
		$this->_role = 'user';
		if ($this->_password == null && isset($data['password']))
		$this->_password = $data['password'];
			
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
		return Zefir_Filter::strToUrl($this->name.'_'.$this->surname);
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
		$row = $this->getDbTable()->findUser($id);

		$this->populate($row);
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
		if ($this->name != null || $this->surname != null)
			$name = $this->name.' '.$this->surname;
		else
			$name = $this->nick;
			
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
			$users[] = $user->populate($row);
		}

		return $users;
	}

	public function prepareFormArray()
	{
		$data = array(
			'user_id' => $this->user_id,
			'name' => $this->name,
			'surname' => $this->surname,
			'nick' => $this->nick,
			'phone' => $this->phone,
			'address' => $this->address,
			'email' => $this->email,
			'show_email' => $this->show_email,
			'role' => $this->_role
		);

		return $data;
	}

	public function setUserRole($role)
	{
		$this->_role = $role;

		return $this;
	}
	
	public function getJurors()
	{
		return $this->getAll(array('role = "juror"'));
	}
}


