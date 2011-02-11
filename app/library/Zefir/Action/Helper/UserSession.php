<?php
/**
 * @package Zefir_Action_Helper_UserSession
 */

/**
 * Handle user's related init procedures
 * @author Zefiryn
 * @since Jan 2011
 */
class Zefir_Action_Helper_UserSession extends Zend_Controller_Action_Helper_Abstract
{
	/**
	 * Zend_View object
	 * @var Zend_View
	 */
	protected $_view;
	
	/**
	 * @see Zend_Controller_Action_Helper_Abstract::preDispatch()
	 */
	public function preDispatch()
	{
		$this->_setUsersRole();
		$this->_checkUsersPrivileges();		
	}
	
	/**
	 * Get the view property of Zend_Action_Controller object
	 * @access private
	 * @return Zend_View an instance of Zend_View class 
	 */
	protected function _getView()
	{
		if (null === $this->_view)
		{
			$controller = $this->getActionController();
			$this->_view = $controller->view;	
		}
		return $this->_view;
				
	}
	
	/**
	 * Sets user's role according to his identity
	 * @access private
	 * @return void
	 */
	protected function _setUsersRole()
	{
		$auth = Zend_Auth::getInstance();
		$view = $this->_getView();

		if (!$auth->hasIdentity())
		{ 
			$user_status = 'Niezalogowany';
			$role = 'guest';
    		
		}
    	else
    	{
    		$user_status = 'Zalogowany';
    		$user = new Application_Model_Users();
    		$role = $user->getDbTable()->getUserRole($auth->getIdentity(), $user);
		}
    	
    	Zend_Registry::set('role', $role);
    	$view->userStatus = $user_status;
    	$view->logged = $auth->hasIdentity();
	}
	
	/**
	 * Check user privilege to the current action
	 * @access private
	 * @return void
	 */
	protected function _checkUsersPrivileges()
	{
		$role =	Zend_Registry::get('role');
		$redirect = new Zend_Controller_Action_Helper_Redirector();
    	$acl = Zend_Registry::get('acl');
		$request = $this->getActionController()->getRequest();
		
		if (!$acl->isAllowed($role, $request->getControllerName(), $request->getActionName()))
		{
			if ($request->getActionName() != 'login')
			{
				//save uri which user tried to get
				$authNamespace = new Zend_Session_Namespace('auth');
				$options = Zend_Registry::get('options');
				$authNamespace->redirect = 
					str_replace($options['resources']['frontController']['baseUrl'], '/', $request->getRequestUri());				
				$this->getActionController()->flashMe('Dostęp do tej części wymaga zalogowania', 'FAILURE');
				
				//save post data
				$request = $this->getRequest();
				if ($request->isPost())
				{
					$post = new Zend_Session_Namespace('post');
					$post->post = $request->getPost();
				}
				$redirect->gotoUrlAndExit('/auth/login');
			}
			else 
			{
				$redirect->gotoUrlAndExit('/index');
			}
		}
		
	}
}