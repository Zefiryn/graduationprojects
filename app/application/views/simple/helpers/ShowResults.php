<?php
class Helper_ShowResults extends Zend_View_Helper_Abstract
{
	public function showResults()
	{
		$appSettings = Zend_Registry::get('appSettings');
		 
		if ($appSettings->result_date <= time() || $this->view->user->_role == 'admin')
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
}