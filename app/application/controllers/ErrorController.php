<?php

class ErrorController extends Zefir_Controller_Action
{

	public function init()
	{
		parent::init();
		$this->view->css = array(
				'simple/error.css'
		);

	}

	public function errorAction()
	{
		$errors = $this->_getParam('error_handler');

		if (!$errors) {
			$this->view->message = 'You have reached the error page';
			return;
		}

		switch ($errors->type) {
			case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ROUTE:
			case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
			case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:

				// 404 error -- controller or action not found
				$this->getResponse()->setHttpResponseCode(404);
				$this->view->message = 'Page not found';
				$this->view->code = 404;
				break;
			default:
				// application error
				$this->getResponse()->setHttpResponseCode(500);
				$this->view->message = 'Application error';
				$this->view->code = 500;
				break;
		}

		// Log exception, if logger available
		if ($log = $this->_startLogger('critical')) {
			$log->log($this->view->message);
			$log->log($errors->exception);
			$log->log(var_export($this->_request->getParams()), true);
		}

		// conditionally display exceptions
		if ($this->getInvokeArg('displayExceptions') == true || $this->user->_role == 'admin') {
			$this->view->exception = $errors->exception;
			$this->view->exceptionTrace = explode("\n", $errors->exception->getTraceAsString());
		}

		$this->view->request = $errors->request;
	}

	public function getLog()
	{
		$bootstrap = $this->getInvokeArg('bootstrap');
		if (!$bootstrap->hasResource('Log')) {
			return false;
		}
		$log = $bootstrap->getResource('Log');
		return $log;
	}


}

