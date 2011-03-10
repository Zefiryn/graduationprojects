<?php
/**
 * @package Zefir_Controller_Action
 */
/**
 * An extension to the default Zend_Controller_Action class
 * @author zefiryn
 */
class Zefir_Controller_Action extends Zend_Controller_Action
{
	/**
	 * init function
	 * @access public
	 * @return void
	 */
	public function init()
	{
		/**
    	 * set the base url for path in the view
    	 */
    	$options = Zend_Registry::get('options');
    	$this->view->baseUrl = $options['resources']['frontController']['baseUrl'];

    	/**
    	 * Check if there is any post data in session from redirect from login
    	 */
    	if ($this->getRequest()->getActionName() != 'login')
    	{
	    	$postSession = Zend_Session::namespaceGet('post');
	    	if (isset($postSession['post']) && $postSession['post'] != NULL)
	    	{
	    		foreach($postSession['post'] as $key => $value)
					$_POST[$key] = $value;
	    	}	
    	}
	}
	
	/**
	 * Check if given action exists in the controller
	 * @access public
	 * @param string $name
	 * @return boolean 
	 */
	public function actionExists($name)
	{
		return method_exists($this, $name.'Action');
	}
	
	/**
	 * Store the flash message
	 * @access private
	 * @param string $msg
	 * @return void
	 */
	public function flashMe($msg, $bg = 'SUCCESS')
	{
		$flash = new Zend_Session_Namespace('flash');
		$flash->message = $msg;
		$flash->message_bg = $bg == 'SUCCESS' ? 'flash_success' : 'flash_failure';
		
	}
	
	/**
	 * Select new name for an existing file
	 * @access private
	 * @param string $dir
	 * @param string $file
	 * @return string $name
	 */
	protected function _getNewName($dir, $file)
	{
		$ext = substr($file, strrpos($file, '.'));
		$rawname = substr($file, 0, strrpos($file, '.'));
		$name = '';
		
		if (file_exists($dir.$file))
		{
			for($i = 1; $name == ''; $i++)
			{
				$tryname = $rawname.'_'.$i.$ext;
				if (!file_exists($dir.$tryname))
					$name = $tryname;
			}
		}
		else 
			$name = $file;
		
		return $name;
	}
	
	/**
     * Set configuration for receiving uploaded file
     * @access private
     * @param string $cache_folder
     * @param Application_Book_Form $form
     * @param string $element
     * @return Zend_Form $form 
     */
    protected function _cacheFile($cache_folder, $form, $element)
    {
    	//get the file element
		$upload = $form->getElement($element);

		if(count($upload->getErrors()) == 0 && !is_array($upload->getFileName()))
		{
			$file = basename($upload->getFileName());
			$dir = APPLICATION_PATH.'/../public'.$cache_folder.'/';
			
			//rename uploaded file if there is another with the same name
			$name = $this->_getNewName($dir, $file);
			$upload->addFilter('Rename', $dir.$name);
			$upload->receive();
			$form->getElement($element.'Cache')->setValue($name);
		}

		return $form;
    }
    
    protected function _redirectToRoute($options, $route)
    {
    	$this->_redirector = $this->_helper->getHelper('Redirector');
		$this->_redirector->gotoRoute($options, $route);
    }
}