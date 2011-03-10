<?php

class Application_Model_DbTable_Users extends Zefir_Application_Model_DbTable
{

    protected $_raw_name = 'users';
	protected $_name = '';
    protected $_primary = 'user_id';

    /**
     * An array of child tables information
     * @var array
     */
    protected $_referenceMap = array();
	
	/**
	 * An array of parent table information
	 * @var array
	 */
	protected $_dependentTables = array(
		'_applications' => 'Application_Model_DbTable_Applications');
	
	/**
	 * Get user role  
	 * @param string $nick
	 * @param Application_Model_Users $user
	 */
	public function getUserRole($nick, $user)
    {
    	$select = $this->select()->where('nick = ?', $nick);
    	$row = $this->fetchRow($select);
    	
    	return ($row->role);
    }
    
    /**
     * Save or update user data in the database 
     * 
     * @param Application_Model_Users $user
     * @throws Zend_Exception
     * @return Application_Model_Users $user
     */
    public function save(Application_Model_Users $user)
    {
    	$id = $user->_user_id;
    	
    	if ($id != null)
    		$row = $this->find($id)->current();
    	
    	else
    	{
    		if ($id != null)
    			throw new Zend_Exception('Incorrect user');
    		else
    			$row = $this->createRow();
    	}
    	
    	$row->nick 			= $user->_nick;
    	$row->name 			= $user->_name;
    	$row->surname		= $user->_surname;
    	$row->address		= $user->_address;
    	$row->phone			= $user->_phone;
    	$row->email			= $user->_email;
    	$row->show_email	= $user->_show_email;
    	$row->role			= $user->_role;
    	if ($user->_password != null)
    		$row->password = sha1($user->_password);
    	
    	if ($row->save())
    	{
    		if (!$id)
    			$user->_user_id = $id = $this->getAdapter()->lastInsertId();
    	}
    	else 
    		throw new Zend_Exception('Couldn\'t save data');
    	
    	return $user;
    }
    
    /**
     * 
     * @param int $id
     * @param Application_Model_Users $user
     */
    public function findUser($id, Application_Model_Users $user)
    {
    	$row = $this->find($id)->current();
    	
    	$user->populateWithReference($row);
    	
    	return $user;
    	
    }

}

