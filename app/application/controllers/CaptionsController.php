<?php

class CAptionsController extends Zefir_Controller_Action
{

    public function init()
    {
        parent::init();
    }

    public function indexAction()
    {
		$request = $this->getRequest();
		$lang = $request->getParam('loc_lang', 'pl');
		
		$caption = new Application_Model_Captions();
		$this->view->captions = $caption->fetchAll($caption->order('name ASC'));
		$this->view->caption_lang = $lang;
    }


    public function newAction() 
    {
    	
    }
}

