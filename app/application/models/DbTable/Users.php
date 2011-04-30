<?php

class Application_Model_DbTable_Users extends Zefir_Application_Model_DbTable
{

    protected $_raw_name = 'users';
	protected $_name = '';
    protected $_primary = 'user_id';

    /**
	 * An array of parent table information
	 * @var array
	 */
	protected $_hasMany = array(
		'applications' => array(
			'model' => 'Application_Model_Applications',
			'refColumn' => 'user_id'
		)
	);
	
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
    	
    	$row->nick 			= $user->nick;
    	$row->name 			= $user->name;
    	$row->surname		= $user->surname;
    	$row->address		= $user->address;
    	$row->phone			= $user->phone;
    	$row->email			= $user->email;
    	$row->show_email	= $user->show_email;
    	if ($user->_role != null)
    		$row->role = $user->_role;
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
    	if (ctype_digit($id))
    		$row = $this->find($id)->current();
    	else
    		$row = $this->fetchRow($this->select()->where('nick = ?', $id));
    		
    	$user->populate($row);
    	
    	return $user;
    	
    }

}

