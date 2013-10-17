<?php

class Application_Model_Diplomas extends GP_Application_Model
{
  public $diploma_id;
  public $edition_id;
  public $name;
  public $surname;
  public $slug;
  public $email;
  public $author_portfolio;
  public $work_site;
  public $show_email;
  public $country;
  public $degree_id;
  public $work_type_id;
  public $graduation_time;
  public $supervisor;
  public $supervisor_degree;
  protected $edition;
  protected $degree;
  protected $files;
  protected $work_type;
  protected $fields;

  protected $_dbTableModelName = 'Application_Model_DbTable_Diplomas';

  public function __construct($id = null, array $options = null)
  {
    return parent::__construct($id, $options);
  }

  public function save()
  {
    $self = parent::save($this);

    if (is_array($this->fields))
    {
      foreach($this->fields as $field)
      {
        $field->diploma_id = $self->diploma_id;
        $field->save();
      }
    }
    if (is_array($this->files)){
      foreach($this->files as $file)
      {
        $file->diploma_id = $self->diploma_id;
        $file->save();
      }
    }
    return $self;
  }

  public function getField($field, $lang)
  {
    if (!is_array($this->fields))
    $this->__get('fields');

    $options = Zend_Registry::get('options');
    $default_language = $options['i18n']['default_language'];

    $entry = '';
    foreach($this->fields as $diplomaField)
    {
      if ($diplomaField->field->field_name == $field && $diplomaField->lang->lang_code == $lang)
      $entry = $diplomaField->entry;
    }
    return  $entry;
  }
  
  public function getFirstFile()
  {
    if ($this->files == null) 
    {
      $this->__get('files');
    }
    
    $file = array_shift($this->files);
    return $file;
  }

  public function getAuthorName()
  {
    return $this->name.' '.$this->surname;
  }

  public function getSupervisor()
  {
    return $this->supervisor_degree.' '.$this->supervisor;
  }

  public function getAdjacentDiplomas()
  {
    return $this->getDbTable()->getAdjacentDiplomas($this);
  }

  public function prepareFormArray()
  {
    $data = array(
      'diploma_id' => $this->diploma_id,
      'degree_id' => $this->degree_id,
      'supervisor' => $this->supervisor,
      'supervisor_degree' => $this->supervisor_degree,
      'name' => $this->name,
      'surname' => $this->surname,
      'email' => $this->email,
      'author_portfolio' => $this->author_portfolio,
      'work_site' => $this->work_site
    );
    $languages = new Application_Model_Languages();
    foreach($languages->fetchAll() as $lang)
    {
      $data[$lang->lang_code] = array(
                'lang_id' => $lang->lang_id,
                'diploma_id' => $this->diploma_id,
                'school' => $this->getField('school', $lang->lang_code),
                'department' => $this->getField('department', $lang->lang_code),
                'work_desc' => $this->getField('work_desc', $lang->lang_code),
                'work_subject' => $this->getField('work_subject', $lang->lang_code),
      );
    }

    return $data;
  }

  public function populateFieldsFromForm($data)
  {
    $field = new Application_Model_Fields();
    $id = $data['diploma_id'];

    $this->name = $data['name'];
    $this->surname = $data['surname'];
    $this->email = $data['email'];
    $this->author_portfolio = $data['author_portfolio'];
    $this->work_site = $data['work_site'];
    $this->supervisor = $data['supervisor'];
    $this->supervisor_degree = $data['supervisor_degree'];
    $this->degree_id = $data['degree_id'] != 0 ? $data['degree_id'] : NULL;

    $this->fields = array();
    $languages = new Application_Model_Languages();
    $fields = new Application_Model_Fields();
    foreach($languages->fetchAll() as $lang)
    {
      $field_data = $data[$lang->lang_code];
      foreach($fields->fetchAll() as $fid => $field) 
      {
        $diplomaField = new Application_Model_DiplomaFields();
        if (!in_array($field->field_name, array('lang_id', 'diploma_id')))
        {
          $diplomaField->diploma_id = $data['diploma_id'];
          $diplomaField->lang_id = $lang->lang_id;
          $diplomaField->field_id = $fid;
          $diplomaField->entry = $field_data[$field->field_name];          
          $this->fields[] = $diplomaField;
        }
      }
    }
    return $this;
  }
  
  public function createFromApp($app)
  {
    $this->populate($app->prepareArchiveArray());
    
    $field = new Application_Model_Fields();
    $lang = new Application_Model_Languages();
    
    foreach($lang->getLanguages() as $lang_id => $code)
    {
      foreach($field->fetchAll() as $id => $field)
      {
        $diplomaField = new Application_Model_DiplomaFields();
        $diplomaField->lang_id = $lang_id;
        $diplomaField->field_id = $id;
        $entry = $field->field_name;
        $diplomaField->entry = $app->getField($entry);
        if ($diplomaField->entry != null) 
        {
          $this->addChild($diplomaField, 'fields');
        }
      }
    }
    
    $i = 0;
    $this->slug = $this->_createSlug();
    $this->_copyFilesFromApplication($app, true);
    $this->save();
    return $this;
    
  }
  
  public function recreateSlug()
  {
    $this->slug = $this->_createSlug();
    return $this;
  }
  
  protected function _copyFilesFromApplication($app, $copyThumbnails = true)
  {
    $edition_folder = $this->_createEditionFolder();
    $diploma_folder = $this->_createDiplomaFolder();
    
    $i = 0;
    foreach($app->files as $file)
    {
      $source = APPLICATION_PATH . '/../public/assets/applications/' . $file->path;
      $dest = $diploma_folder .'/'. substr($file->path, strrpos($file->path, '/') + 1);
      if (copy($source, $dest))
      {
        $path = substr($dest, strpos($dest, $this->edition->edition_name));
        $diplomaFile = new Application_Model_DiplomaFiles();
        $diplomaFile->position = ++$i;
        $diplomaFile->path = $path;
        if (!$copyThumbnails)
        {
          //make thumbnails from scrathc
          $diplomaFile->resizeImage();
        }
        else
        {
          //copy thumbnails from applications
          foreach($diplomaFile->getThumbnails() as $key)
          {
            $thumbSource = APPLICATION_PATH . '/../public/assets/applications/' . $file->getImage($key);
            $thumbDest = $diploma_folder .'/'. substr($file->getImage($key), strrpos($file->getImage($key), '/') + 1);
            try {
              copy($thumbSource, $thumbDest);
            }
            catch (Zend_Exception $e){
              //in case copying is not successfull create the thumbnails
              $diplomaFile->recreateThumbnails();
            }
          }
        }

        $this->addChild($diplomaFile, 'files');
      }
    }
    
    return $this;
  }
  
  protected function _createDiplomaFolder()
  {
    $dir = APPLICATION_PATH . '/../public/assets/editions/' . $this->__get('edition')->edition_name;
    $name = Zefir_Filter::strToUrl($this->surname . '_' . $this->name);
    
    if (!file_exists($dir . '/' . $name))
    {
      mkdir($dir . '/' . $name);
    }
    
    return $dir . '/' . $name;
  }

  protected function _createEditionFolder()
  {
    $dir = APPLICATION_PATH . '/../public/assets/editions/' . $this->__get('edition')->edition_name;
    if (!file_exists($dir))
    {
      mkdir($dir);
    }

    return $dir;
  }
  
  protected function _createSlug()
  {
    return Zefir_Filter::strToUrl($this->getAuthorName());
  }
  
  public function findBySlug($slug)
  {
    $row = $this->getDbTable()->findBySlug($slug);
    
    if ($row)
    {
      $this->populate($row);
    }
    
    return $this;
  } 
}

