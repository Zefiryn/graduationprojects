<?php
/**
 * @package Zefir_Validate_DatePeriod
 */

/** @see Zend_Validate_Abstract */
require_once 'Zend/Validate/Abstract.php';

/**
 */
class Zefir_Validate_DatePeriod extends Zend_Validate_Abstract
{
	/**
     * Error codes
     * @const string
     */
    const TO_EARLY	= 'dateToEarly';
    const TO_LATE	= 'dateToLate';
    
	/**
	* Error messages
	* @var array
	*/
	protected $_messageTemplates = array(
		self::TO_EARLY 	=> 'Given date is earlier than the initial date',
		self::TO_LATE	=> 'Given date is later than the expiration date',
    );
    
    protected $_startDate;
    protected $_endDate;
    
	/**
	* Sets validator options
	*
	* @param  mixed $token
	* @return void
	*/
	public function __construct($startDate = null, $endDate = null)
	{
		$this->_setStartDate($startDate);
		$this->_setEndDate($endDate);
	}
	
	protected function _setStartDate($startDate)
	{
		$this->_startDate = $startDate;
	}
	protected function _setEndDate($endDate)
	{
		$this->_endDate = $endDate;
	}
	
	protected function _getStartDate()
	{
		return $this->_startDate;
	}
	protected function _getEndDate()
	{
		return $this->_endDate;
	}
	
	
	/**
	*
	* @param  mixed $value
	* @param  array $context
	* @return boolean
	*/
	public function isValid($value)
	{
		$this->_setValue($value);
		
		$startDate = $this->_getStartDate();
		$endDate = $this->_getEndDate();
		
		$date = strtotime($value);
		
		if ($date < $startDate)
		{
			$this->_error(self::TO_EARLY);
			return FALSE;
		}
		
		if ($date > $endDate)
		{
			$this->_error(self::TO_LATE);
			return FALSE;
		}
		return TRUE;
    }
}