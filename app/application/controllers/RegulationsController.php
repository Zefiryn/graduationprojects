<?php

class RegulationsController extends Zefir_Controller_Action
{

    public function init()
    {
        parent::init();
    }

    public function indexAction()
    {
		$regulations = new Application_Model_Regualtions();
		$this->view->regulations = $regulations->getRegulations();
    }


}

