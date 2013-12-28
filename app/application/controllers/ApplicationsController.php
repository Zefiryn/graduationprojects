<?php

class ApplicationsController extends Zefir_Controller_Action
{
  public function init()
  {
    parent::init();
    $this->view->css = array(
      'simple/applications.css', 'simple/projects.css'
    );    
  }

  public function indexAction()
  {
    Zefir_Pqp_Classes_Console::logSpeed('start action');
    
    $currentStage = $this->_getCurrentStage();
    $application = new Application_Model_Applications();
    
    $sort = $this->_getSort(). ' ' . $this->_getSortOrder();
    $applications = $application->getApplications($sort, $currentStage, $this->_getWorkType(), $this->_getFilter(), $this->_getRange(), $this->_getCountrySelection(), $this->view->user);
    
    $stages = new Application_Model_Stages();
    $jurors = new Application_Model_Jurors();
    $jurors = $jurors->fetchAll();
      
    //if (!$currentStage->isFinalStage()) {
      $this->view->statistics = $this->_createStatistics($applications);
    //}
    $this->view->applications = $applications;
    $this->view->stages = $stages->fetchAll();
    if ($this->view->user->_role == 'admin' && $currentStage /* && !$currentStage->isFinalStage()*/)
    {
      $this->view->votes = $this->_getAllVotes($currentStage, $applications, $jurors);
    }
    $this->view->currentStage = $currentStage;
    $this->view->filterOptions = $this->_getFilterOptions();
    $this->view->selection = array('stage' => $this->_getCurrentStage()->order,
                    'sort' => $this->_getSort(),
                    'work_type' => $this->_getWorkType(),
                    'sort_order' => $this->_getSortOrder(),
                    'filter' => $this->_getFilter(),
                    'range' => $this->_getRange());

    if ($this->getRequest()->isXMLHttpRequest()) 
    {
      $this->_helper->layout()->disableLayout();
      $this->_helper->viewRenderer->setNoRender(true);
//      if($currentStage->isFinalStage()) 
//      {
//        $this->renderScript('applications/_final_stage.phtml');
//      }
//      else 
//      {
        $this->renderScript('applications/_table_data.phtml');
//      }
    }
  }
  
  public function exportAction() {
    $this->_helper->layout()->disableLayout();
    $this->_helper->viewRenderer->setNoRender(true);
    
    $currentStage = $this->_getCurrentStage();
    $application = new Application_Model_Applications();
    
    $sort = $this->_getSort(). ' ' . $this->_getSortOrder();
    $applications = $application->getApplications($sort, $currentStage, $this->_getWorkType(), $this->_getFilter(), $this->_getRange(), $this->_getCountrySelection(), $this->view->user);
    
    $path = APPLICATION_PATH.'/../public/assets/cache/applications_stage'.$currentStage->stage_id.'.csv';
    $handle = fopen($path, 'w');
    $fields = array('#', 'Numer zgłoszenia', 'Imię i nazwisko', 'Email', 'Tytuł', 'Strona projektu', 'Kraj', 'Typ', 'Dyplom', 'Opis', 'Opis angielski');
    fputcsv($handle, $fields);
    $i = 1;
    foreach($applications as $application) {
      $fields = array(
          $i++,
          $application->application_id,
          $application->user->getUserFullName(),
          $application->user->email,
          $application->work_subject,
          $application->work_site,
          $this->view->translate($application->country),
          $application->work_type->work_type_name,
          $this->view->translate($application->degree->degree_name),
          $application->work_desc,
          $application->work_desc_eng
          
      );
      fputcsv($handle, $fields);
    }
    
    $response = $this->getResponse();
    $response->setHeader('Content-type', 'application/octet-stream');
    $response->setHeader('Content-Disposition', 'attachment; filename="applications_stage'.$currentStage->stage_id.'.csv"');
    readfile($path);
  }

  public function newAction()
  {
    if (!$this->_checkDeadline())
    {
      $this->flashMe('application_deadline_has_passed');
      $this->_redirectToRoute(array(), 'root');
    }
    
    $this->view->css[] = 'simple/forms.css';
    $appSettings = Zend_Registry::get('appSettings');
    $options = Zend_Registry::get('options');

    $form = new Application_Form_Application('new');
    $form->setAction($this->view->url(array(), 'application_new'));
    $form->setDecorators(array(
      array('ViewScript', array('viewScript' => 'forms/_applicationForm.phtml'))
    ));

    $session = new Zend_Session_Namespace('applicationForm');
    if (isset($session->form))
    {
      $values =  $session->form;
      $form->populate($values);
    }
     
    $request = $this->getRequest();

    if ($request->isPost())
    {
      //form has been submited
        
      if($request->getParam('leave', null))
      {
        $this->_log('leaving');
        $this->_deleteCachedFiles($request->getPost());
        $this->flashMe('cancel_edit');
        $this->_redirectToRoute(array(), 'root');
      }
        
      $work_type = new Application_Model_WorkTypes();
      $work3d = array_search('3d', $work_type->getWorkTypes());
      if ($request->getPost('work_type_id') == $work3d ) {
        //model_3d required if worktype is 3d
        $form->getElement('model_3d')->setRequired(true);
        if ($request->getPost('model_3d') == 1) {
          //model scale is required if model_3d is true
          $form->getElement('model_scale')->setRequired(true);        
        }
      }      
      if ($form->isValid($request->getPost()) || count($form->getMessages()) == 0)
      {        
        $cached = $this->_checkFileCache('new');
        $this->_handleFiles($form, $cached);      
      
        //form is valid
        if (!$form->getSubForm('file_1')->getElement('file_1')->hasErrors())
        {
          $form = $this->_createFileOrder($form);
          $session->form = $form->getValues();          
          $this->view->form = $form;
          $this->view->path = array(
          0 => array('route' => 'root', 'data' => array(), 'name' => array('main_page')),
          1 => array('route' => 'lang_application_new', 'data' => array('lang' => $this->view->lang), 'name' => array('form_link')),
          );
          $this->renderScript('applications/confirm.phtml');
        }

      }
      else
      {//form has errors
        $cached = $this->_checkFileCache('new');
        $this->_handleFiles($form, $cached);      
        $this->_log('Form values:');
        $params= $form->getValues();$params['user']['password']=null;$params['user']['password_check']=null;
        $this->_log($params);
        $this->_log('Form errors:');
        $this->_log($form->getMessages());
      }
    }
    else
    {//no form has been submited

      $form->getElement('edition_id')->setValue($appSettings->edition->edition_id);
        
    }

    $form = $this->_createFileOrder($form);
    $this->view->form = $form;
    $this->view->path = array(
    0 => array('route' => 'root', 'data' => array(), 'name' => array('main_page')),
    1 => array('route' => 'lang_application_new', 'data' => array('lang' => $this->view->lang), 'name' => array('form_link')),
    );
  }

  public function saveAction()
  {
    $request = $this->getRequest();
    $this->view->css[] = 'simple/forms.css';
     
    if ($request->getParam('confirm'))
    {
      //add application
      $session = new Zend_Session_Namespace('applicationForm');
      $user = new Application_Model_Users();
      $data = $session->form;
      $user->populateFromForm($data['user']);
      $user->save();

      if ($user->user_id != null)
      {
        $application = new Application_Model_Applications();
        $application->populateFromForm($session->form);
        $application->user_id = $user->user_id;
        $application->save();
        unset($session->form);
        $this->_sendConfirmationMail($user);
        $this->flashMe('application_added', 'SUCCESS');
        $this->_redirectToRoute(array(), 'root');
        $this->render('delete');
      }
    }
    else
    {
      $this->_redirectToRoute(array(), 'application_new');
    }
  }

  public function editAction()
  {
    if (!$this->_checkDeadline())
    {
      $this->flashMe('application_deadline_has_passed');
      $this->_redirectToRoute(array(), 'root');
    }
    $this->view->css[] = 'simple/forms.css';
    $appSettings = Zend_Registry::get('appSettings');
    $options = Zend_Registry::get('options');
    $form = new Application_Form_Application('edit');
    $form->removeElement('personal_data_agreement');
    $form->setAction('/applications/edit');
    $form->setDecorators(array(
    array('ViewScript', array('viewScript' => 'forms/_applicationEditForm.phtml'))
    ));

    $request = $this->getRequest();

    if ($request->isPost())
    { 
      $id = $request->getParam('application_id', '');
      if ($this->view->user->_role != 'admin'
      && !isset($this->view->user->applications[0])
      && $this->view->user->applications[0]->application_id != $id)
      {
        $this->flashMe('not_allowed', 'FAILURE');
        $this->_redirect('/index');
      }

      if($request->getParam('leave', null))
      {
        $this->_redirectToRoute(array(), 'applications');
      }
      
      $cached = $this->_checkFileCache('edit');
  
      if ($form->isValid($request->getPost()) || count($form->getMessages()) == 0)
      {
        $this->_handleFiles($form, $cached, 'edit');
        if (!$form->getSubForm('file_1')->getElement('file_1')->hasErrors())
        {
          $application = new Application_Model_Applications();
          $application->populateFromForm($form->getValues());
          $application->save();
          
          $this->flashMe('application_edited', 'SUCCESS');
          $this->_redirectToRoute(array('id' => $application->application_id), 'application');
        }

      }
      else
      {
        $this->_handleFiles($form, $cached, 'edit');
      }
    }
    else
    {
      $id = $request->getParam('id', '');
        
      if ($this->view->user->_role != 'admin'
      && !isset($this->view->user->applications[0])
      && $this->view->user->applications[0]->application_id != $id)
      {
        //$this->flashMe('not_allowed', 'FAILURE');
        //$this->_redirect('/index');
      }
        
      //populate form
      if ($id != null)
      {
        $application = new Application_Model_Applications($id);
        if ($application->application_id != null)
        $form->populate($application->prepareFormArray());
        else
        {
          throw new Zend_Exception('Wrong id given');
        }
      }
      else
      throw new Zend_Exception('Wrong id given');
    }

    $this->view->form = $form;
  }

  public function deleteAction()
  {
    $request = $this->getRequest();
    $id = $request->getParam('id', '');

    if ($this->view->user->_role != 'admin'
      && $this->user->applications[0]->application_id != $id)
    {
      $this->flashMe('not_allowed', 'FAILURE');
      $this->_redirect('/index');
    }
    $application = new Application_Model_Applications($id);
    $application->delete();
    
    $this->_helper->layout()->disableLayout();
    $this->_helper->viewRenderer->setNoRender(true);
    $this->flashMe('application_deleted', 'SUCCESS');    
    echo json_encode(array('link' => $this->view->url(array(), 'applications')));
    
  }

  public function archiveAction() {
    $id = $this->getRequest()->getParam('id', null);
    if ($id) {
      $application = new Application_Model_Applications();
      $results = $application->archive($id);
        if ($results['hasError'] == true) 
        { 
          $this->flashMe($results[$id]['error'], 'ERROR');
        }
        else {
          $this->flashMe('application_archived', 'SUCCESS');
        }
    }
    else {
      $this->flashMe('application_no_id', 'ERROR');
    }
    $this->_helper->layout()->disableLayout();
    $this->_helper->viewRenderer->setNoRender(true);    
    $this->_redirectToRoute(array(), 'applications');
  }

  public function showAction()
  {
    $request = $this->getRequest();

    $id = $request->getParam('id', '');

    if ($id == null)
    throw new Zend_Exception('Wrong id parameter');

    $application = new Application_Model_Applications($id);
      
    if ($this->view->user->role == 'admin'
    || $this->view->user->role == 'juror'
    || $this->view->user->user_id == $application->user->user_id)
    {
      $stages = new Application_Model_Stages();
      $currentStage = $this->_getCurrentStage();
        
      $this->view->application = $application;
      $this->view->currentStage = $currentStage;
      $this->view->stages = $stages->fetchAll();
      $this->view->adjacent = $application->getAdjacentApplication();
        
      if ($this->view->user->_role == 'admin')
      {
        $jurors = new Application_Model_Jurors();
        $jurors = $jurors->fetchAll();
        $this->view->votes = $this->_getAllVotes($currentStage, array($application), $jurors);
      }
    }
    else
    {
      $this->flashMe('not_allowed', 'FAILURE');
      $this->_redirect('/index');
    }
  }
  
  public function getstageAction()
  {
    $request = $this->getRequest();
    $id = $request->getParam('id', '');
    
    if ($id == null)
      throw new Zend_Exception('Wrong id parameter');
    
    $application = new Application_Model_Applications($id);
      
    if ($this->view->user->role == 'admin'
    || $this->view->user->role == 'juror'
    || $this->view->user->user_id == $application->user->user_id)
    {
      $this->_helper->layout()->disableLayout();
      $this->_helper->viewRenderer->setNoRender(true);
            
      $stages = new Application_Model_Stages();
      $currentStage = $this->_getCurrentStage();
      
      $this->view->application = $application;
      $this->view->currentStage = $currentStage;
      $this->view->stages = $stages->fetchAll();
      
      if ($this->view->user->_role == 'admin')
      {
        $jurors = new Application_Model_Jurors();
        $jurors = $jurors->fetchAll();
        $this->view->votes = $this->_getAllVotes($currentStage, array($application), $jurors);
      }
      else
      {
        $this->view->votes = null;
      }
      echo Zend_Json::encode(array('html' => 
          $this->view->partial('applications/vote-box.phtml', array(
            'application' => $application,
            'currentStage' => $currentStage,
            'stages' => $stages->fetchAll(),
            'votes' => $this->view->votes,
            'user' => $this->view->user
          ))
      ));
    }
    else
    {
      $this->flashMe('not_allowed', 'FAILURE');
      $this->_redirect('/index');
    }
  }

  public function deleteImageAction()
  {
    $request = $this->getRequest();
    if ($request->isXmlHttpRequest())
    {
      $id = $request->getParam('id');

      if (ctype_digit($id))
      {
        //already sent image
        $file = new Application_Model_Files($id);
        if ($this->view->user->user_id == $file->application->user->user_id || $this->view->user->role == 'admin')
        {
          $file->delete();
          $this->_helper->json(array("file_id" => $id));
        }
        else
        {
          $this->_helper->json(array("access" => 0), FALSE);
        }
      }
      else
      {//cached image
        $path = APPLICATION_PATH.'/../public/assets/cache/'.$id;
        unlink($path);
        $this->_helper->json(array("file_d" => $id), FALSE);
      }
    }
    else
    {
      $this->flashMe('ajax_only', 'FAILURE');
      $this->_redirectToRoute(array(), 'root');
    }
  }
  
  public function voteAction()
  {
    $this->_helper->layout()->disableLayout();
    $this->_helper->viewRenderer->setNoRender(true);
    
    $this->view->css[] = 'simple/forms.css';
    $request = $this->getRequest();
    $post = $request->getPost();

    if (!isset($post['application_id']) 
      || !isset($post['stage_id']) 
      || !isset($post['juror_id']))
    {
      echo Zend_Json::encode(array('error' => 'We are unable to process your request due to missing data'));
    }
    else
    {
      $vote = new Application_Model_Votes();
      $juror = new Application_Model_Jurors($post['juror_id']);
      $stage = new Application_Model_Stages($post['stage_id']);
      $app = new Application_Model_Applications($post['application_id']);
      
      if ($this->view->user->_role == 'admin' || $stage->active == 1)
      {
        $vote->find(array(
            'stage_id' => $post['stage_id'], 
            'juror_id' => $post['juror_id'],
            'application_id' => $post['application_id']));
        
        if ($vote->isEmpty())
        {
          $vote->populate($post);
        }
        
        $vote->vote = $post['vote'] * $juror->wage;
        try {
          $vote->save();
          $cache = Zend_Registry::get('cache');
          $cache->clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG, array('Application_Model_Jurors'));
          echo Zend_Json::encode(array('success' => $post['vote'], 
                          'grade' => $vote->vote, 
                          'appScore' => $app->countScore($post['stage_id']),
                          'qualified' => $app->inStage($stage->getNextStage())
                          ));
        }
        catch (Zend_Exception $e) {
          echo Zend_Json::encode(array('error' => $e->getMessage()));
        }
      }
      else
      {
        echo Zend_Json::encode(array('error' => $this->view->translate('stage_closed')));
      }
      
    }
  }
  
  public function disputeAction()
  {
    $request = $this->getRequest();
    
    $dispute = new Application_Model_Disputes();
    $data = array('application_id' => $request->getParam('id'), 
          'user_id' => $this->view->user->user_id );
    $dispute = $dispute->find($data);
    
    if ($dispute->isEmpty())
    {
      $dispute->populateFromForm($data);
      $dispute->save();
    }
    
    if ($request->isXMLHttpRequest())
    {
      $application = new Application_Model_Applications($data['application_id']);
      $this->_helper->layout()->disableLayout();
      $this->_helper->viewRenderer->setNoRender(true);
      echo Zend_Json::encode(array('succcess' => $application->application_id, 
                              'dispute' => $this->view->partial('applications/_dispute.phtml', 
                                array('application' => $application))
                            ));
    }
    else
    {
      $this->flashMe('objection_noted');
      $this->_redirectToRoute(array(), 'applications');
    }
    
  }
  
  public function removedisputeAction()
  {
    $request = $this->getRequest();
    $dispute = new Application_Model_Disputes();
    $data = array('application_id' => $request->getParam('id'), 
          'user_id' => $this->view->user->user_id );
    $dispute = $dispute->find($data);
    
    if ($this->view->user->user_id == $dispute->user_id || $this->view->user->_role == 'admin')
    {
      $dispute->delete();
      if ($request->isXMLHttpRequest())
      {
        $application = new Application_Model_Applications($data['application_id']);
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        echo Zend_Json::encode(array('succcess' => $application->application_id, 
                    'dispute' => $this->view->partial('applications/_dispute.phtml', 
                              array('application' => $application))
                  ));
      }
      else
      {
        $this->flashMe('objection_noted');
        $this->_redirectToRoute(array(), 'applications');
      }
    }
    else
    {
      if ($request->isXMLHttpRequest())
      {
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        Zend_Json::encode(array('error' => 'no_access'));
      }
      else
      {
        $this->flashMe('no_access');
        $this->_redirectToRoute(array(), 'applications');
      }
    }
    
    
  }
  
  public function resultAction() 
  {
    if (!$this->_checkResultDate())
    {
      $this->flashMe('application_result_before');
      $this->_redirectToRoute(array(), 'root');
    }
    $edition = new Application_Model_Editions();
    
    if ($edition->findPublicEdition())
    {
      $this->view->diplomas = $edition->diplomas;
      $this->view->edition_name = $edition->edition_name;
      $this->renderScript('diplomas/index.phtml');
    }
    else
    {
      $stage = new Application_Model_Stages();
      $stage->getFinalStage();
      
      $application = new Application_Model_Applications();
      $applications = $application->getApplications('surname' , $stage, null, null, $this->view->user);
      $this->view->applications = $applications;
    }
    
    $this->view->path = array(
    0 => array('route' => 'root', 'data' => array(), 'name' => array('main_page')),
    1 => array('route' => 'results', 'data' => array(), 'name' => array('best_works_link')),
    );
    
  }

  protected function _checkFileCache($type = 'new')
  {
    $params = $this->getRequest()->getParams();
    $options = Zend_Registry::get('options');
    $appSettings = Zend_Registry::get('appSettings');
    $cached = FALSE;
     
    for($i = 1; $i <= $appSettings->max_files; $i++)
    {
      $fileCache = isset($params['file_'.$i]['file_'.$i.'Cache']) ? $params['file_'.$i]['file_'.$i.'Cache'] : null;
        
      if ($type == 'new')
      $file = APPLICATION_PATH.'/../public'.$options['upload']['cache'].'/'.$fileCache;
      else
      $file = APPLICATION_PATH.'/../public'.$options['upload']['applications'].'/'.$fileCache;

      if ($fileCache != null && file_exists($file))
      $cached = TRUE;
    }

    return $cached;
  }

  protected function _handleFiles($form, $cached, $type = 'new')
  {
    $appSettings = Zend_Registry::get('appSettings');
    $options  = Zend_Registry::get('options');
    $newFile = FALSE;
    for($i = 1; $i <= $appSettings->max_files; $i++)
    {
      $sf = $form->getSubForm('file_'.$i);
      $this->_cacheFile($options['upload']['cache'], $sf, 'file_'.$i);
      if ($sf->getElement('file_'.$i.'Cache')->getValue() != null)
      {
        $newFile = TRUE;
      }
    }

    if (!$cached && !$newFile)
    {
      //add error to the first file field as there are no cached nor new uploaded files
      $form->getSubForm('file_1')->getElement('file_1')->addError($this->view->translate('fileUploadErrorNoFile'));
    }

    return $form;
  }

  protected function _createFileOrder($form)
  {
    $present = array();
    $empty = array();
    $appSettings = Zend_Registry::get('appSettings');
    for($i = 1; $i <= $appSettings->max_files; $i++)
    {
      if ($form->getSubForm('file_'.$i)->getElement('file_'.$i.'Cache')->getValue() != null)
      $present[] = 'file_'.$i;
      else
      $empty[] = 'file_'.$i;
    }

    $order = array_merge($present, $empty);
    $form->fileOrder = $order;
    return $form;
  }

  protected function _deleteCachedFiles($data)
  {
    $dir = APPLICATION_PATH.'/../public/assets/';
     
    for($i = 1; $i <= 10; $i++)
    {
      if ($data['file_'.$i]['file_'.$i.'Cache'] != null &&
      file_exists($dir.$data['file_'.$i]['file_'.$i.'Cache']))
      {
        unlink($dir.$data['file_'.$i]['file_'.$i.'Cache']);
      }
    }
  }

  protected function _createStatistics($applications)
  {
    $work_type = new Application_Model_WorkTypes();
    $types = $work_type->getWorkTypes();
    unset($types[0]);

    $languages = array('cz', 'pl', 'sk', 'hu', 'all');
     
    $statistics = array();
     
    foreach($languages as $language)
    {
      foreach($types as $type)
      {
        $statistics[$language][$type] = 0;
      }
      $statistics[$language]['all'] = 0;
    }
     
    foreach ($applications as $application)
    {
      isset($statistics['all'][$application->work_type->work_type_name]) ? $statistics['all'][$application->work_type->work_type_name]++ : $statistics['all'][$application->work_type->work_type_name] = 1;
      isset($statistics['all']['all']) ? $statistics['all']['all']++ : $statistics['all']['all'] = 1; 
      isset($statistics[$application->country][$application->work_type->work_type_name]) ? $statistics[$application->country][$application->work_type->work_type_name]++ : $statistics[$application->country][$application->work_type->work_type_name] = 1;
      isset($statistics[$application->country]['all']) ? $statistics[$application->country]['all']++ : $statistics[$application->country]['all'] = 1;
    }
    
    return $statistics;
  }

  protected function _getSort()
  {
    $request = $this->getRequest();
    $sortApplication = new Zend_Session_Namespace('app_sort');
     
    $currentSort = $request->getParam('sort', NULL);

    if (!$currentSort)
    {
      $currentSort = $sortApplication->sort ? $sortApplication->sort : 'surname';
    }
    
    //save current sort
    $sortApplication->sort = $currentSort;
    return $currentSort;
  }
  
  protected function _getWorkType()
  {
    $request = $this->getRequest();
    $type = new Zend_Session_Namespace('work_type');
     
    $currentType = $request->getParam('work_type', NULL);

    if (!$currentType)
    {
      $currentType = $type->type ? $type->type : 'all';
    }
    
    //save current sort
    $type->type = $currentType;
    return $currentType;
  }
  
  protected function _getSortOrder()
  {
    $request = $this->getRequest();
    $sortOrder = new Zend_Session_Namespace('sort_order');
      
    $order = $request->getParam('sort_order', NULL);
  
    if (!$order)
    {
      $order = $sortOrder->order ? $sortOrder->order : 'asc';
    }
  
    //save current sort
    $sortOrder->order = $order;
    return $order;
  }
  
  protected function _getFilter() 
  {
    $request = $this->getRequest();
    $savedFilter = new Zend_Session_Namespace('filter');
    $filter = $request->getParam('filter', null);
    
    if (!$filter)
    {
      $filter = $savedFilter->filter ? $savedFilter->filter : 'all';
    }
    
    $savedFilter->filter = $filter;
    return $filter;
    
  }
  
  protected function _getRange()
  {
    $request = $this->getRequest();
    $savedRange = new Zend_Session_Namespace('range');
    $range = array(
      'start' => $request->getParam('rangeStart', $savedRange->start),
      'end' => $request->getParam('rangeEnd', $savedRange->end),
    );
    
    $savedRange->start = $range['start'];
    $savedRange->end= $range['end'];
    
    return $range;
  }

  protected function _getCountrySelection()
  {
    $request = $this->getRequest();
    $savedCountry = new Zend_Session_Namespace('country');
    $code = $request->getParam('countrySelect', $savedCountry->code);
    
    $savedCountry->code = $code;
    
    return $code;
  }


  protected function _sendConfirmationMail($user)
  {
    $appSettings = Zend_Registry::get('appSettings');
    $mail = new Zend_Mail('UTF8');
     
    $mail->setFrom('no-reply@2plus3d.pl', $this->view->translate('confirmation_email_from'));
    $mail->addTo($user->email, $user->getUserFullName());
    $mail->setSubject($this->view->translate('confirmation_email_subject'));

    $body = $this->view->translate('confirmation_email_body');
    $body = sprintf($body, date($appSettings->date_format, $appSettings->application_deadline), $user->nick);
    $mail->setBodyText($body);
    $mail->send();
  }

  protected function _checkDeadline()
  {
    $appSettings = Zend_Registry::get('appSettings');
     
    if ($appSettings->application_deadline >= time() || $this->view->user->_role == 'admin')
    {
      return TRUE;
    }
    else
    {
      return FALSE;
    }
  }
  
  protected function _checkResultDate()
  {
    $appSettings = Zend_Registry::get('appSettings');
    
    if ($appSettings->result_date <= time() || $this->view->user->_role == 'admin')
    {
      return TRUE;
    }
    else
    {
      return FALSE;
    }
  }
  
  protected function _getCurrentStage()
  {
    $request = $this->getRequest();
    $stage = $request->getParam('stage', null);
    $savedStage = new Zend_Session_Namespace('stage');

    $stage = $stage ? $stage :  $savedStage->stage;
    
    if (!$stage)
    {
      
      $stageObj = new Application_Model_Stages();
      $stageObj->populate($stageObj->getDbTable()->fetchAll(null, 'order')->current());
    }
    else 
    {
      $stageObj = new Application_Model_Stages();
      $stageObj->getBy('order', $stage);
    }
    
    $savedStage->stage = $stage;
    return $stageObj;
  }
  
  protected function _getAllVotes($stage, $applications, $jurors)
  {
    $vote = new Application_Model_Votes();
    $appVotes = $vote->getVotesByJurors($stage->stage_id);
    
    $votes = array();
    foreach($applications as $application)
    {
      //get current application votes
      $appVote = (isset($appVotes[$application->application_id])) ? $appVotes[$application->application_id] : array();
      
      foreach($jurors as $juror)
      {
        
        $vote = isset($appVote[$juror->juror_id]) ? $appVote[$juror->juror_id] : -1;
        $votes[$application->application_id][$juror->juror_id] = array('juror_name' => $juror->juror_name,
                                        'wage' => $juror->wage,
                                        'vote' => (int)$vote); 
      } 
    }
    
    return $votes;
    
  }
  
  protected function _getFilterOptions()
  {
    $stages = new Application_Model_Stages();
    $options = array(
      'stages' => $stages->getList(),
      'sort' => array('user_name_and_surname' => 'surname',
              'country' => 'country',
              'work_type' => 'work_type_id',
              'work_subject' => 'work_subject',
              'degree' => 'degree_id'),
      'sort_order' => array('asc', 'desc'),
      'filter' => array('all' => 'all_applications',
                'disputed' => 'disputed',
                'unmarked' => 'unmarked',
                'qualified' => 'qualified',
                'notqualified' => 'notqualified',
                'range' => 'filter_range_point',
                'country' => 'country')
    );
    
    return $options;
  }

  public function winningvoteAction() {
    $id = $this->_request->getParam('appId', null);
    if ($id) {
      try {
        $app = new Application_Model_Applications($id);      
        $app->setWinner();
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        echo Zend_Json::encode(array('success' => true));
      }
      catch (Exception $e) {
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        echo Zend_Json::encode(array('error' => $e->getMessage()));
      }
    }
  }
}

