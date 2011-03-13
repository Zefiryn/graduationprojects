<?php
/**
 * @package Zefir_Validate_Unique
 */

/**
 * @see Zend_Validate_Db_Abstract
 */
require_once 'Zend/Validate/Db/Abstract.php';

/**
 * Confirms a record does not exist in a table.
 *
 * @package    Zefir_Validate_Unique
 * @uses       Zend_Validate_Db_Abstract
 */
class Zefir_Validate_Unique extends Zend_Validate_Db_Abstract
{
	/**
     * Error constants
     */
    const ERROR_USER_EXIST 	= 'userExist';
    const ERROR_EMAIL_EXIST	= 'emailExist';

    
    public function isValid($value)
    {
    	//set additional messages
    	$msgTemplates = array(
    		self::ERROR_USER_EXIST => 'This name has already been taken',
    		self::ERROR_EMAIL_EXIST => 'This e-mail has already been used', 
    	);
    	
    	$this->_messageTemplates += $msgTemplates;
    	
        $valid = true;
        $this->_setValue($value);

        $result = $this->_query($value);
        if ($result) 
        {
            $valid = false;

            if ($this->getField() == 'nick')
            	$this->_error(self::ERROR_USER_EXIST);
            elseif ($this->getField() == 'email')
            	$this->_error(self::ERROR_EMAIL_EXIST);
            else
            	$this->_error(self::ERROR_RECORD_FOUND);
        }

        return $valid;
    }
}
