<?php
/**
 * @package Zefir_Action_Helper_Localization
 */

/**
 * Set and create translation array
 * @author Zefiryn
 * @since Feb 2011
 */
class Zefir_Action_Helper_Localization extends Zend_Controller_Action_Helper_Abstract
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
		$this->_setLanguage();
		$this->_getTranslationTable();
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
	
	private function _setLanguage()
	{
		$options = Zend_Registry::get('options');
		$auth = Zend_Auth::getInstance();
		$langSession = new Zend_Session_Namespace('lang');
		$request = $this->getRequest();
		
		$lang = $request->getParam('lang', '');
		
		if ($lang != NULL)	
			$lang = substr($lang, 0, strrpos($lang, '/'));
		
		elseif (isset($_COOKIE['lang']))
			$lang = $_COOKIE['lang'];
		
		elseif (isset($langSession->lang))
			$lang = $langSession->lang;

		elseif ($auth->hasIdentity())
		{
			$user = new Application_Model_Users();
			$lang = $user->getDbTable()->getUserLanguage($auth->getIdentity());
		}
		else
			$lang = $options['i18n']['default_language'];
			
		//check if the template is correct
		if ($this->_langExists($lang))
			$lang = $lang;
		else
			$lang = $options['i18n']['default_language'];

		//set current value in cookie and in session
		setcookie("lang",$lang, time() + (60*60*24*14) , '/');
		$langSession->lang = $lang;
		
		return $lang;	
	}
	
	private function _langExists($lang)
	{
		$localization = new Application_Model_Localizations();
		return ($localization->getDbTable()->isLocalization($lang));	
	}
	
	private function _getTranslationTable()
	{
		$view = $this->_getView();
		$langSession = new Zend_Session_Namespace('lang');
		
		$lang = new Application_Model_Localizations();
		$translations = $lang->getTranslationTables();
		
		Zend_Registry::set('translations', $translations);
		$view->translations = $translations;
		$view->lang = $langSession->lang;
	}
}