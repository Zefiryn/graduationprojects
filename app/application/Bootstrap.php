<?php
/**
 * Bootstrap file
 * @package Bootstrap
 */
/**
 * Bootstrap class
 */
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
	/**
	 * Main init
	 * @access private
	 * @return void
	 */
	protected function _init()
	{
		Zend_Session::start();
	}
	
	/**
	 * Init configuration
	 * @access private
	 * @return void
	 */
	protected function _initConfig()
	{
		Zend_Registry::set('options', $this->getOptions());
		
		//set the db
		$config = Zend_Registry::get('options');
		$db = Zend_Db::factory($config['resources']['db']['adapter'], $config['resources']['db']['params']);
		Zend_Db_Table::setDefaultAdapter($db);
	}
	
	/**
	 * Init View class
	 * @access private
	 * @return Zend_View an instance of Zend_View class or similar
	 */
	protected function _initView() {

		$view = new Zefir_View_Template();
		
		$viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('ViewRenderer');
		$viewRenderer->setView($view)
					->setViewScriptPathSpec(':controller/:action.:suffix')
					->setViewScriptPathNoControllerSpec(':action.:suffix')
					->setViewSuffix('phtml');

		//initialize layout
    	$layout = Zend_Layout::getMvcInstance();
    	$layout->setViewSuffix('phtml');
    	
    	return $view;
    	
	}
	
	/**
	 * Init placeholders for the view
	 * @access private
	 * @return void
	 */
	protected function _initPlaceholders() {
		
		$this->bootstrap('view');
		$view = $this->getResource('view');
		$view->assign('doctype', 'XHTML1_STRICT');
		
		// Set the initial title
      	$view->headTitle('Domowa biblioteczka')->setSeparator(' :: ');
        
      	//set the js files
      	$options = Zend_Registry::get('options');
     	$view->headScript()->prependFile($options['resources']['frontController']['baseUrl'].'js/jquery.easing.1.3.js');
     	$view->headScript()->prependFile($options['resources']['frontController']['baseUrl'].'js/jquery.min.js');
        
	}
	
	/**
	 * Init routing routines
	 * @access private
	 * @return void
	 */
	protected function _initRouting()
	{
		$this->bootstrap('frontController');
		$router = $this->getResource('frontController')->getRouter();
		
	}
	
	/**
	 * Init access controll list
	 * @access private
	 * @return Zend_Acl $acl
	 */
	protected function _initAcl()
	{
		require_once 'Zend/Acl/Role.php';
		require_once 'Zend/Acl/Resource.php';
		
		$acl = new Zend_Acl();
		$guestRole = new Zend_Acl_Role(new Zend_Acl_Role('guest'));
		
		$acl->addRole($guestRole);
			//->addRole(new Zend_Acl_Role('user'), $guestRole);

		//resources
		$acl->addResource(new Zend_Acl_Resource('error'));
		$acl->addResource(new Zend_Acl_Resource('index'));
		
		//clearance
		$acl->allow(null, array('error', 'index'), null);
		$acl->allow('guest', array('index', 'error'), array('index', 'show'));
		
		Zend_Registry::set('acl', $acl);
		
		return $acl;
	}
	
	/**
	 * Init action helpers
	 * @access private
	 * @return void
	 */
	protected function _initActionHelpers()
	{
		Zend_Controller_Action_HelperBroker::addHelper(
			new Zefir_Action_Helper_UserSession()
			);
		Zend_Controller_Action_HelperBroker::addHelper(
			new Zefir_Action_Helper_Flash()
			);
	}

}

