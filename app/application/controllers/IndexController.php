<?php

class IndexController extends Zefir_Controller_Action
{

    public function init()
    {
		parent::init();  
    }

    public function indexAction()
    {
        $main = $this->view->translations;
		ksort($main);
		$this->view->main = $main;
    }


}

