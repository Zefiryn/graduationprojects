<?php

class MigrationsController extends Zefir_Controller_Action
{

	public function init()
	{
		parent::init();
	}
	
  public function copyAction() {
    $edition = new Application_Model_Editions();
    $edition->getEdition('2012–2013', TRUE);
    $fields = new Application_Model_Fields();
    $fields = array(2 => 'work_subject', 1 => 'school', 4 => 'department', 3 => 'work_desc');
    
    foreach ($edition->diplomas as $project) {
      $fieldsVals = array();
      foreach($fields as $fieldId => $code) {
        $fieldsVals[$fieldId] = $project->getField($code, 'en');        
      }
      foreach($project->fields as $diplomaField) {
        if (!in_array($diplomaField->lang->lang_code, array('en', 'pl'))) {
          $diplomaField->entry = $fieldsVals[$diplomaField->field_id];
          $diplomaField->save();
        }
      }      
    }
  }
}

