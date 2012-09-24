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
class GP_Action_Helper_Partners extends Zend_Controller_Action_Helper_Abstract
{

	/**
	 * @see Zend_Controller_Action_Helper_Abstract::preDispatch()
	 */
	public function preDispatch()
	{
		$view = $this->getActionController()->view;
		
		$partners = new Application_Model_Partners();
		$view->organizers = $partners->getByType('organizer');
		$view->media = $partners->getByType('media');		 
	}	
}