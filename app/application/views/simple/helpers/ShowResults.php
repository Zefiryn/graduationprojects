<?php
class Helper_ShowResults extends Zend_View_Helper_Abstract
{
	public function showResults()
	{
		$edition = new Application_Model_Editions();	
	
		return ($edition->findPublicEdition());
	}
}