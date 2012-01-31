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
		$this->view->captions = $caption->getDbTable()->fetchAll(null, 'name ASC');
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
				if ($request->isXMLHttpRequest())
				{
					$form->removeElement('csrf');
				}
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
					try {
						$localization->save();
						if (!$request->isXMLHttpRequest())
						{
							$this->flashMe('translation_edited');
							$this->_redirectToRoute(array('loc_lang' => $lang), 'localization');
						}
						else
						{
							$this->_helper->layout()->disableLayout();
							$this->_helper->viewRenderer->setNoRender(true);
							echo Zend_Json::encode(array('translation' => $localization->text));
						}
					}
					catch (Zend_Exception $e){
						$this->_log($e->getMessage(), 'error');
						$this->_log($e->getTrace(), 'error');
						if (!$request->isXMLHttpRequest())
						{
							$this->flashMe('error_occured');
							$this->_redirectToRoute(array('loc_lang' => $lang), 'localization');
						}
						else
						{
							$this->_helper->layout()->disableLayout();
							$this->_helper->viewRenderer->setNoRender(true);
							echo Zend_Json::encode(array('error' => $this->view->translate('saving_error')));
						}
					}					
					
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
			if ($request->isXMLHttpRequest())
			{
				$this->_helper->json($data);
			}
			else
			{
				$this->flashMe('translation_deleted');
				$this->_redirectToRoute(array('loc_lang' => $lang), 'localization');
			}
		}
	}
}

