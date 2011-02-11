<?php
/**
 * @package Zefir_Filter
 */
/**
 * Class with methods dealing with filtering strings with multibyte support
 * @author zefiryn
 * @since Jan 2011
 */
class Zefir_Filter
{
	/**
	 * Constructor
	 * @access public
	 * @return void
	 */
	public function __construct()
	{}
	
	/**
	 * Get the first character of the given string
	 * @access public
	 * @static 
	 * @param string $string
	 * @param boolean $upper
	 * @param string $encoding
	 * @return string
	 */
	public static function getFirstLetter($string, $upper = TRUE, $encoding = 'utf8')
	{
		if ($upper == TRUE)
			return mb_strtoupper(mb_substr($string, 0, 1, $encoding), $encoding);
		else
			return mb_substr($string, 0, 1, $encoding);
	}

	/**
	 * Get the last character of the given string
	 * @access public
	 * @static 
	 * @param string $string
	 * @param boolean $upper
	 * @param string $encoding
	 * @return string
	 */
	public static function getLastChar($string, $upper = TRUE, $encoding = 'utf8')
	{
		if ($upper == TRUE)
			return mb_strtoupper(mb_substr($string, -1, 1, $encoding), $encoding);
		else
			return mb_substr($string, -1, 1, $encoding);
	}
}

?>