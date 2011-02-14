<?php

class AboutController extends Zefir_Controller_Action
{

    public function init()
    {
        parent::init();
    }

    public function indexAction()
    {
    	$about = $this->view->translations[$this->view->lang]['about_text'];
		$this->view->about = explode("\n", $about);
    }


}

