<?php

class FaqController extends Zefir_Controller_Action
{

    public function init()
    {
        parent::init();
    }

    public function indexAction()
    {
		$faqs = new Application_Model_Faqs();
		$this->view->faqs = $faqs->getFaqs();
    }


}

