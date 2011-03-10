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
		$view->addHelperPath('Zefir/View/Helper', 'Zefir_View_Helper');
		
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
      	$view->headTitle('Graduation Projects')->setSeparator(' :: ');
        
      	//set the js files
      	$options = Zend_Registry::get('options');
     	$view->headScript()->prependFile($options['resources']['frontController']['baseUrl'].'js/jquery.easing.1.3.js');
     	$view->headScript()->prependFile($options['resources']['frontController']['baseUrl'].'js/jquery-ui-1.8.9.custom.min.js');
     	$view->headScript()->prependFile($options['resources']['frontController']['baseUrl'].'js/jquery.min.js');
     	$view->headScript()->appendFile($options['resources']['frontController']['baseUrl'].'js/fancybox/jquery.fancybox-1.3.1.pack.js');
     	$view->headScript()->appendFile($options['resources']['frontController']['baseUrl'].'js/fancybox/jquery.mousewheel-3.0.2.pack.js');
        
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
		
		$route = new Zend_Controller_Router_Route_Regex(
    			'^([a-z]{2}/)?([a-z]+)/?([a-z]+)?$',
    			array(),
    			array(	1 => 'lang', 
    					2 => 'controller',
    					3 => 'action'));
    	$router->addRoute('def', $route);
    	
    	/**
    	 * regulation routing
    	 */
    	$route = new Zend_Controller_Router_Route(
    			'regulations/:edition',
    			array(
    				'controller' => 'regulations',
    				'action' => 'show'    				
    			),
    			array('edition' => '^[0-9]{4}-[0-9]{4}$'));
    	$router->addRoute('regulation', $route);
    	
    	$route = new Zend_Controller_Router_Route(
    			'regulations/edit/:edition',
    			array(
    				'controller' => 'regulations',
    				'action' => 'edit'    				
    			),
    			array('edition' => '^[0-9]{4}-[0-9]{4}$'));
    	$router->addRoute('regulation', $route);
    	
    	$route = new Zend_Controller_Router_Route(
    			':lang/regulations/:edition',
    			array(
    				'controller' => 'regulations',
    				'action' => 'show'    				
    			),
    			array('edition' => '^[0-9]{4}-[0-9]{4}$',
    				  'lang' => '^[a-z]{2}$'));
    	$router->addRoute('regulation_lang', $route);
    	
    	$route = new Zend_Controller_Router_Route(
    			':lang/regulations/edit/:edition',
    			array(
    				'controller' => 'regulations',
    				'action' => 'edit'    				
    			),
    			array('edition' => '^[0-9]{4}-[0-9]{4}$',
    			'lang' => '^[a-z]{2}$'));
    	$router->addRoute('regulation', $route);
    	
    	/**
    	 * LOGIN
    	 */
    	$route = new Zend_Controller_Router_Route(
    			':lang/login',
    			array(
    				'controller' => 'auth',
    				'action' => 'login'    				
    			),
    			array('lang' => '^[a-z]{2}$'));
    	$router->addRoute('langLogin', $route);
    	
    	$route = new Zend_Controller_Router_Route(
    			'login',
    			array(
    				'controller' => 'auth',
    				'action' => 'login'    				
    			));
    	$router->addRoute('login', $route);
    	
    	/**
    	 * APPLICATIONS
    	 */
    	
    	$route = new Zend_Controller_Router_Route(
    			':lang/application/:id',
    			array(
    				'controller' => 'applications',
    				'action' => 'show'    				
    				),
    			array(	'lang' => '^[a-z]{2}$',
    					'id' => '^[0-9]+$')
    			
    			);
    	$router->addRoute('lang_application', $route);
    	
    	$route = new Zend_Controller_Router_Route(
    			'application/:id',
    			array(
    				'controller' => 'applications',
    				'action' => 'show'    				
    				),
    			array('id' => '^[0-9]+$')
    			
    			);
    	$router->addRoute('application', $route);
    	
    	$route = new Zend_Controller_Router_Route(
    			'application/edit/:id',
    			array(
    				'controller' => 'applications',
    				'action' => 'edit'    				
    				),
    			array('id' => '^[0-9]+$')
    			
    			);
    	$router->addRoute('edit_application', $route);
    	
    	$route = new Zend_Controller_Router_Route(
    			'application/delete/:id',
    			array(
    				'controller' => 'applications',
    				'action' => 'delete'    				
    				),
    			array('id' => '^[0-9]+$')
    			
    			);
    	$router->addRoute('delete_application', $route);
    	
    	/**
    	 * user
    	 */
    	
    	$route = new Zend_Controller_Router_Route(
    			':lang/user/:id',
    			array(
    				'controller' => 'users',
    				'action' => 'show'    				
    				),
    			array(	'lang' => '^[a-z]{2}$',
    					'id' => '^[0-9]+$')
    			
    			);
    	$router->addRoute('lang_user', $route);
    	
    	$route = new Zend_Controller_Router_Route(
    			'user/:id',
    			array(
    				'controller' => 'users',
    				'action' => 'show'
    				),
    			array('id' => '^[0-9]+$')
    			
    			);
    	$router->addRoute('user', $route);
    	
    	$route = new Zend_Controller_Router_Route(
    			'user/edit/:id',
    			array(
    				'controller' => 'users',
    				'action' => 'edit'
    				),
    			array('id' => '^[0-9]+$')
    			
    			);
    	$router->addRoute('edit_user', $route);
    	
    	$route = new Zend_Controller_Router_Route(
    			':lang/user/edit/:id',
    			array(
    				'controller' => 'users',
    				'action' => 'edit'
    				),
    			array(	'id' => '^[0-9]+$',
    					'lang' => '^[a-z]{2}$')
    			
    			);
    	$router->addRoute('lang_edit_user', $route);
    	
    	$route = new Zend_Controller_Router_Route(
    			'user/delete/:id',
    			array(
    				'controller' => 'users',
    				'action' => 'delete'
    				),
    			array('id' => '^[0-9]+$')
    			
    			);
    	$router->addRoute('delete_user', $route);
    	
    	$route = new Zend_Controller_Router_Route(
    			':lang/user/delete/:id',
    			array(
    				'controller' => 'users',
    				'action' => 'delete'
    				),
    			array(	'id' => '^[0-9]+$',
    					'lang' => '^[a-z]{2}$')
    			
    			);
    	$router->addRoute('lang_delete_user', $route);
    	
    	$route = new Zend_Controller_Router_Route(
    			'user/promote/:id',
    			array(
    				'controller' => 'users',
    				'action' => 'promote'
    				),
    			array('id' => '^[0-9]+$')
    			
    			);
    	$router->addRoute('promote_user', $route);
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
		
		$acl->addRole($guestRole)
			->addRole(new Zend_Acl_Role('user'), $guestRole)
			->addRole(new Zend_Acl_Role('juror'), 'user')
			->addRole(new Zend_Acl_Role('admin'), 'juror');

		//resources
		$acl->addResource(new Zend_Acl_Resource('error'));
		$acl->addResource(new Zend_Acl_Resource('index'));
		$acl->addResource(new Zend_Acl_Resource('about'));
		$acl->addResource(new Zend_Acl_Resource('regulations'));
		$acl->addResource(new Zend_Acl_Resource('faq'));
		$acl->addResource(new Zend_Acl_Resource('auth'));
		$acl->addResource(new Zend_Acl_Resource('contact'));
		$acl->addResource(new Zend_Acl_Resource('applications'));
		$acl->addResource(new Zend_Acl_Resource('users'));
		
		//clearance
		$acl->allow(null, array('error', 'index'), null);
		$acl->allow(null, array('about', 'regulations', 'faq'), array('index', 'show'));
		$acl->allow(null, array('auth'), array('index', 'login'));
		$acl->allow(null, array('users'), array('show', 'edit', 'restore', 'delete'));
		$acl->allow(null, array('contact'), null);
		$acl->allow('user', array('auth'), array('logout'));
		
		$acl->allow(null, array('applications'), array('new'));
		$acl->allow('user', array('applications'), array('show', 'edit', 'update'));
		$acl->allow('juror', array('applications'), array('index', 'vote'));
		$acl->allow('juror', array('users'), array('index'));
		$acl->deny('juror', array('applications'), array('edit', 'update'));
		$acl->allow('admin', array('applications', 'regulations', 'faq', 'about', 'users'), null);

		
		
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
		Zend_Controller_Action_HelperBroker::addHelper(
			new Zefir_Action_Helper_Localization()
			);
		Zend_Controller_Action_HelperBroker::addHelper(
			new GP_Action_Helper_AppSettings()
			);
	}
	
	protected function _initMail()
	{
		
		$options = Zend_Registry::get('options');
		
		$transport = new Zend_Mail_Transport_Smtp($options['mail']['host'], $options['mail']['smtp']);
		Zend_Mail::setDefaultTransport($transport);
	}
	
}

