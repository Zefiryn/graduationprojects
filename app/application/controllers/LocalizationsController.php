<?php

class LocalizationsController extends Zefir_Controller_Action
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


    public function editAction() 
    {
    	$request = $this->getRequest();
		$lang = $request->getParam('loc_lang', 'pl');
		
		
		$id = $request->getParam('id', null);
		
		if ($id)
		{
			$form = new Application_Form_Localization();
			$caption = new Application_Model_Captions($id);
			$localization = $caption->getTranslationObject($lang);
			
			if ($request->isPost())
			{
				if ($request->getPost('leave', null))
				{
					$this->flashMe('cancel_edit');
					$this->_redirectToRoute(array('loc_lang' => $lang), 'translation');
				}
				if ($form->isValid($request->getPost()))
				{
					$language = new Application_Model_Languages();
					
					$localization->caption_id = $caption->caption_id;
					$localization->lang_id = $language->findLangId($lang); 
					$localization->text = $form->getElement('text')->getValue();
					$localization->save();
					
					$this->flashMe('translation_edited');
					$this->_redirectToRoute(array('loc_lang' => $lang), 'localization');
				}
			}
			else
			{
				$form->populate($localization->toArray());
			}
			
			$this->view->caption_lang = $lang;
			$this->view->localization = $localization;
			$this->view->form = $form;
		}
    }
}

