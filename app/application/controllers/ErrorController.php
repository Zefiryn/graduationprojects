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
        $saveToLog = false;
        break;
      default:
        // application error
        $this->getResponse()->setHttpResponseCode(500);
        $this->view->message = 'Application error';
        $this->view->code = 500;
        $saveToLog = true;
        break;
    }

    // Log exception, if logger available
    if ($saveToLog && $log = $this->_startLogger('critical')) {
      $log->log($this->view->message,Zend_Log::DEBUG);
      $log->log($errors->exception,Zend_Log::DEBUG);
      $params = var_export($this->_request->getParams(),true);
      $log->log($params,Zend_Log::DEBUG);
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

