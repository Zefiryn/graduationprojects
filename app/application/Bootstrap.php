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
		//Zend_Session::start();
		iconv_set_encoding('internal_encoding','UTF-8');
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
	 * Init routing routines
	 * @access private
	 * @return void
	 */
	protected function _initRouting()
	{
		$frontController = $this->bootstrap('frontController');
		$router = $this->getResource('frontController')->getRouter();
		//router->removeDefaultRoutes();

		$route = new Zend_Controller_Router_Route_Regex(
    			'^([a-z]{2}/)?([a-z]+)/?([a-z]+)?$',
		array(),
		array(	1 => 'lang',
		2 => 'controller',
		3 => 'action'));
		$router->addRoute('def', $route);
		 
		$route = new Zend_Controller_Router_Route(
    			':lang/',
		array(	'controller' => 'news',
    					'action' => 'index'),
		array('lang' => '^[a-z]{2}$'));
		$router->addRoute('main', $route);
		 
		$route = new Zend_Controller_Router_Route(
    			'login',
		array(	'controller' => 'auth',
    					'action' => 'login'));
		$router->addRoute('login', $route);
		 
		$route = new Zend_Controller_Router_Route(
    			':lang/login',
		array(	'controller' => 'auth',
    					'action' => 'login'),
		array('lang' => '^[a-z]{2}$'));
		$router->addRoute('lang_login', $route);
		 
		$route = new Zend_Controller_Router_Route(
    			'logout',
		array(	'controller' => 'auth',
    					'action' => 'logout'));
		$router->addRoute('logout', $route);
		 
		$route = new Zend_Controller_Router_Route(
    			':lang/logout',
		array(	'controller' => 'auth',
    					'action' => 'logut'),
		array('lang' => '^[a-z]{2}$'));
		$router->addRoute('lang_logout', $route);
		/**
		 * add routing from the ini file
		 */
		 
		$route_config = new Zend_Config_Ini(APPLICATION_PATH.'/configs/route.ini', 'production');
		$router->addConfig($route_config, 'routes');
		 
		 
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
		$acl->addResource(new Zend_Acl_Resource('admin'));
		$acl->addResource(new Zend_Acl_Resource('work-types'));
		$acl->addResource(new Zend_Acl_Resource('schools'));
		$acl->addResource(new Zend_Acl_Resource('settings'));
		$acl->addResource(new Zend_Acl_Resource('localizations'));
		$acl->addResource(new Zend_Acl_Resource('captions'));
		$acl->addResource(new Zend_Acl_Resource('languages'));
		$acl->addResource(new Zend_Acl_Resource('editions'));
		$acl->addResource(new Zend_Acl_Resource('news'));
		$acl->addResource(new Zend_Acl_Resource('migrations'));
		$acl->addResource(new Zend_Acl_Resource('diplomas'));
		$acl->addResource(new Zend_Acl_Resource('votes'));
		$acl->addResource(new Zend_Acl_Resource('jurors'));
		$acl->addResource(new Zend_Acl_Resource('stages'));

		//clearance
		$acl->allow(null, array('error', 'index'), null);
		$acl->allow(null, array('about', 'regulations', 'faq'), array('index', 'show'));
		$acl->allow(null, array('auth'), array('index', 'login'));
		$acl->allow(null, array('users'), array('show', 'edit', 'restore', 'delete'));
		$acl->allow(null, array('contact'), null);
		$acl->allow(null, array('applications'), array('new', 'save'));
		$acl->allow(null, array('diplomas', 'news'), array('index', 'show'));
		$acl->allow(null, array('schools'), array('find'));
		$acl->allow('user', array('auth'), array('logout'));
		$acl->allow('user', array('applications'), array('new', 'show', 'edit', 'delete-image'));
		$acl->deny('user', array('admin'), null);

		$acl->allow('juror', array('applications'), array('index', 'show', 'vote'));
		//$acl->deny('juror', array('applications'), array('new', 'edit'));
		$acl->allow('juror', array('users'), array('show', 'edit', 'delete'));
		$acl->deny('juror', array('users'), array('new', 'index'));
		$acl->allow('juror', array('admin'), null);
		$acl->allow('juror', array('localizations'), null);
		$acl->allow('juror', array('captions'), array('index','new','edit'));
		$acl->allow('juror', array('schools'), array('index','new','edit'));
		$acl->allow('juror', array('diplomas'), array('index','show','edit'));
		$acl->allow('juror', array('about'), null);
		$acl->allow('juror', array('faq'), null);
		$acl->allow('juror', array('regulations'), null);
		$acl->allow('juror', array('votes'), ('vote'));

		$acl->allow('admin', array('users'), array('new', 'index'));
		$acl->allow('admin', null, null);
		$acl->allow('admin', 'applications', null);
		$acl->allow('admin', array('votes'), ('settings'));

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

}

