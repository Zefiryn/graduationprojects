<?php

class VotesController extends Zefir_Controller_Action
{
  public function init()
  {
    parent::init();
    $this->view->css = array(
        'simple/applications.css', 'simple/forms.css'
    );
  }
  
  public function settingsAction()
  {
    $jurors = new Application_Model_Jurors();
    $stages = new Application_Model_Stages();
    $users = new Application_Model_Users();
    
    
    $this->view->jurors = $jurors->fetchAll();
    $this->view->stages = $stages->fetchAll();
    $this->view->users = $users->getJurors();
    $this->render('newsettings');
  }
  
  public function newstageAction()
  {
    $form = new Application_Form_Stage();
    $request = $this->getRequest();
    if ($request->isPost())
    {
      if($request->getParam('leave', null))
      {
        $this->flashMe('cancel_edit');
        $this->_redirectToRoute(array(), 'vote_settings');
      }
      if ($form->isValid($request->getParams()))
      {
        $stage = new Application_Model_Stages();
        $stage->populateFromForm($form->getValues());
        $stage->save();
        $this->flashMe('stage_added');
        $this->_redirectToRoute(array(), 'vote_settings');
      }
    }
    $this->view->form = $form;
  }
}