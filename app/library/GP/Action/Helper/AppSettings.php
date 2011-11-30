<?php
/**
 * @package GP_Action_Helper_UserSession
 */

/**
 * Get settings from the database
 * @author Zefiryn
 * @since Jan 2011
 * @package GP_Action_Helper_UserSession
 */
class GP_Action_Helper_AppSettings extends Zend_Controller_Action_Helper_Abstract
{

	/**
	 * @see Zend_Controller_Action_Helper_Abstract::preDispatch()
	 */
	public function preDispatch()
	{
		$settings = new Application_Model_Settings();
		$settings = $settings->getDbTable()->getSettings($settings);

		 
		$tplSettings = new Application_Model_TemplateSettings();
		$tpl = new Zend_Session_Namespace('template');
		$tplSettings = $tplSettings->findTemplateByName($tpl->template_name);

		Zend_Registry::set('tplSettings', $tplSettings);
		Zend_Registry::set('appSettings', $settings);

		$this->_setEdition($settings);

	}

	protected function _setEdition($settings)
	{
		/*
		 $request =	$this->getRequest();
		$edition = $request->getParam('edition', '');

		if (strstr($edition, '-'))
		{
		$edition = str_replace('-', '/', $edition);
		}

		$editions = new Application_Model_Editions();
		if (!$editions->editionExists($edition))
		$edition = FALSE;

		//if the edition not exist try to get the one saved in session
		if (!$edition)
		{
		$session = new Zend_Session_Namespace('edition');

		if (isset($session->edition))
		{
		$edition =  $session->edition;
		if (!$editions->editionExists($edition, TRUE))
		$edition =  $settings->edition->edition_name;
		}
		else
		{
		$edition =  $settings->edition->edition_name;
		}
		}
		*/

		Zend_Registry::set('edition', $settings->edition->edition_name);
		$session = new Zend_Session_Namespace('edition');
		$session->edition = $settings->edition->edition_name;
		$this->getActionController()->view->edition = $settings->edition->edition_name;

		$editions = new Application_Model_Editions();
		$this->getActionController()->view->edition_list = $editions->getEditions('DESC');
	}
}