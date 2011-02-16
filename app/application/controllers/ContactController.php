<?php

class ContactController extends Zefir_Controller_Action
{

    public function init()
    {
        parent::init();
    }

    public function indexAction()
    {
    	$form = new Application_Form_Contact();
    	$this->view->form = $form;
    }

    public function sendAction()
    {
        // action body
    }


}



