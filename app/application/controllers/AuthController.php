<?php
/**
 * @package AuthController
 */
/**
 * Authentication controller
 * @author zefiryn
 * @since Feb 2011
 */
class AuthController extends Zefir_Controller_Action
{

	/**
	 * Inital 
	 * @access public
	 * @return void
	 */
    public function init()
    {
        parent::init();
    }

    public function indexAction()
    {
        $this->loginAction();
        $this->_helper->viewRenderer('login');
    }

    /**
	 * Handle login
	 * @access public
	 * @return void
	 */
    public function loginAction()
    {
        $db = Zend_Db_Table::getDefaultAdapter();
		$request = $this->getRequest();
		$loginForm = new Application_Form_Login();
		$loginForm->setDecorators(array(
						array('ViewScript', array('viewScript' => 'forms/_loginForm.phtml'))
						));
		
		if ($request->isPost()) 
		{
			if ($loginForm->isValid($request->getPost())) 
			{
				$options = Zend_Registry::get('options');
				$adapter = new Zend_Auth_Adapter_DbTable(
								$db,
								$options['resources']['db']['params']['prefix'].'users',
								'nick',
								'password',
								'SHA1(?)');
							
				$adapter->setIdentity($loginForm->getValue('nick'));
				$adapter->setCredential($loginForm->getValue('password'));	 
							
				$authentication = Zend_Auth::getInstance();
				$result = $authentication->authenticate($adapter);	 

				if ($result->isValid()) 
				{	
					//reset the template settings
					$templateSession = new Zend_Session_Namespace('template');
					$templateSession->template_name = NULL;
					
					//reset template in cookie
					setcookie("template_name", null, time() - 60*60 , '/');
					
					//redirect to the page from
					$authNamespace = Zend_Session::namespaceGet('auth');
					
					$this->flashMe('login_success', 'SUCCESS');
					
					if (isset($authNamespace['redirect']) 
						&& $authNamespace ['redirect'] != NULL)
					{
						$newAuth = new Zend_Session_Namespace('auth');
						$newAuth->redirect = NULL;
						$this->_redirect($authNamespace['redirect']);
					}
					else
						$this->_redirect('/index');
					
				}
				else
				{
					$this->view->wrongLogin = TRUE;
				}
	
				
			}
		}
		$this->view->loginForm = $loginForm;
		$this->view->headTitle('Logowanie');
    }

	/**
	 * Handle logout
	 * @access public
	 * @return void
	 */    
    public function logoutAction()
    {
        Zend_Auth::getInstance()->clearIdentity();
		$templateSession = new Zend_Session_Namespace('template');
		$templateSession->template_name = NULL;
		setcookie("template_name", null, time() - 60*60 , '/');
		$this->flashMe('logout_success', 'SUCCESS');
		$this->_redirect('/index');
    }


}





