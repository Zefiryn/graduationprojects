<?php


class Zefir_View_Helper_Date extends Zend_View_Helper_Abstract
{

	public function date($string, $format = "")
	{
		
		return strtotime($string);
	}
}
