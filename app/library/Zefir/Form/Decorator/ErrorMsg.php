<?php

class Zefir_Decorator_ErrorMsg extends Zend_Form_Decorator_Abstract
{ 
	
	protected $_errorContener;
	
	/**
     * Render errors
     *
     * @param  string $content
     * @return string
     */
    public function render($content)
    {
     	$form = $this->getElement();
     	
     	$attribs		= $form->getAttribs();
     	$name 			= $form->getFullyQualifiedName();
     	$errors 		= $form->getErrors();
		$messages 		= $form->getMessages();
		$tag			= isset($attribs['tag']) ?  $attribs['tag'] : 'div';
		$errorMsgTag 	= isset($attribs['errorMsgTag']) ?  $attribs['errorMsgTag'] : 'p';
		$class			= 'error-div';
		$errorMsgClass	= 'error';
		
		//create contener element where errors will be placed
		$this->_errorContener = $this->_createErrorContener($tag, $class, $name);

		if ($form->hasErrors())
        {
        	$errorContent = '';
        	
        	foreach($messages as $error => $errorMsg)
        	{
        		$errorContent .= $this->_createErrorMsg($errorMsgTag, $errorMsgClass, $errorMsg);
        	}
        	
        	$errorContent = sprintf($this->_errorContener, $errorContent);
        	
	    	switch ($this->getPlacement()) {
	            case self::APPEND:
	                return $content . $this->getSeparator() . $errorContent;
	            case self::PREPEND:
	                return $errorContent . $this->getSeparator() . $content;
	        }
        }
        else
        	return $content;
    }
    
    protected function _createErrorContener($tag, $class, $name)
    {
    	return '<'.$tag.' id="error-'.$name.'" class="'.$class.'">%s</'.$tag.'>';
    }
    
	protected function _createErrorMsg($tag, $class, $msg)
    {
    	return '<'.$tag.' class="'.$class.'">'.$msg.'</'.$tag.'>';
    }
}