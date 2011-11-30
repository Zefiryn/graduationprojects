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
		$localization = new Application_Model_Localizations();

		$caption = new Application_Model_Captions();
		$this->view->captions = $caption->fetchAll($caption->order('name ASC'));
		$this->view->translations = $localization->getTranslationFromDb();
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
			$form->getElement('text')->setDescription($caption->name);
			$form->setDecorators(array(
			array('ViewScript', array('viewScript' => 'forms/_translationForm.phtml'))
			));
			$form->setData(array('lang'=>$lang));
				
			if ($request->isPost())
			{
				if ($request->getPost('leave', null))
				{
					$this->flashMe('cancel_edit');
					$this->_redirectToRoute(array('loc_lang' => $lang), 'localization');
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
			$this->view->caption = $caption;
			$this->view->localization = $localization;
			$this->view->form = $form;
		}
	}

	public function deleteAction()
	{
		$request = $this->getRequest();
		$id = $request->getParam('id', null);
		$lang = $request->getParam('loc_lang', null);

		if ($lang && $id)
		{
			$caption = new Application_Model_Captions($id);
			$localization = $caption->getTranslationObject($lang);
			$localization->delete();
			$this->flashMe('translation_deleted', 'SUCCESS');
			$data = array(0 => $this->view->url(array('loc_lang' => $lang), 'localization'));
			$this->_helper->json($data);
		}
	}
}

