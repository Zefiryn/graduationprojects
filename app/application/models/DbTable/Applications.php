<?php

class Application_Model_DbTable_Applications extends Zefir_Application_Model_DbTable
{

	/**
	 * Name of the table without prefix
	 * @var string
	 */
	protected $_raw_name = 'applications';

	/**
	 * Primary key of the table
	 * @var string
	 */
	protected $_primary = 'application_id';


	protected $_belongsTo = array(

    	'edition' => array(
    		'model' => 'Application_Model_Editions',
    		'column' => 'edition_id',
			'refColumn' => 'edition_id'
		),

       	'user' => array(
       		'model' => 'Application_Model_Users',
			'column' => 'user_id',
			'refColumn' => 'user_id'
		),

		'school' => array(
       		'model' => 'Application_Model_Schools',
			'column' => 'school_id',
			'refColumn' => 'school_id'
		),

		'degree' => array(
       		'model' => 'Application_Model_Degrees',
			'column' => 'degree_id',
			'refColumn' => 'degree_id'
		),

		'work_type' => array(
       		'model' => 'Application_Model_WorkTypes',
			'column' => 'work_type_id',
			'refColumn' => 'work_type_id'
		),
		
	);
	 

	/**
	 * An array of parent table information
	 * @var array
	 */
	protected $_hasMany = array(
		'files' => array(
			'model' => 'Application_Model_Files',
			'refColumn' => 'application_id',
		),
		'votes' => array(
			'model' => 'Application_Model_Votes',
			'refColumn' => 'application_id',
		), 
	);

	/**
	 * constructor
	 * @access public
	 * @param array $config
	 * @return void
	 */
	public function __construct($config = array())
	{
		parent::__construct(array());
	}

	public function getAllApplications($sort)
	{
		$user = new Application_Model_Users();
		$user_table = $user->getDbTable()->getTablename();
		 
		$work_type = new Application_Model_WorkTypes();
		$work_type_table = $work_type->getDbTable()->getTablename();
		 
		$select = $this->select()
		->setIntegrityCheck(FALSE)
		->from(array('a' => $this->_name))
		->joinLeft(array('u' => $user_table), 'a.user_id = u.user_id')
		->joinLeft(array('w' => $work_type_table), 'a.work_type_id= w.work_type_id')
		->order($sort);
		
		return $this->fetchAll($select); 
	}

	/**
	 * Save or update application data in the database
	 *
	 * @param Application_Model_Applications $application
	 * @throws Zend_Exception
	 * @return Application_Model_Applications $application
	 */
	public function save(Application_Model_Applications $application)
	{
		if ($application->application_id != null)
		$oldData = new Application_Model_Applications($application->application_id);
		else
		$oldData = null;

		//add new school if any was given
		$application = $this->_addNewSchool($application);
		 
		//check if there is user added
		if ($application->user_id == null && isset($application->user))
		$application->user_id = $application->user->user_id;
		 
		//save application data
		try {
			$application = parent::save($application);
			$application->user = new Application_Model_Users($application->user_id);
			 
		} catch (Zend_Exception $e) {

			throw $e;
		}
		 
		//create user directory
		$userDir = $this->_getUserDir($application, $application->user, $oldData);
		 
		//copy uploaded files
		$this->_saveUserFiles($application, $application->user, $userDir, $oldData);
		 
		//$application->files = $files;

		return $application;
		 
	}

	public function delete(Application_Model_Applications $application)
	{
		$options = Zend_Registry::get('options');
		 
		//remove files
		$this->_deleteApplicationFiles($application->files);
		 
		//remove miniature
		$path = APPLICATION_PATH.'/../public'.$options['upload']['miniatures'].'/'.$application->miniature;
		unlink($path);
		 
		//remove user
		$application->user->delete();
		 
		//remove application
		parent::delete($application);
	}

	protected function _addNewSchool($application)
	{
		$school = new Application_Model_Schools();
		$school->getSchoolByName($application->school->school_name);

		if ($school->school_id == NULL)
		{
			$school->school_name = $application->school->school_name;
			$school->save();
			if ($school->school_id == null)
			throw new Zend_Exception('Couldn\'t add new school');
		}
		$application->school = $school;
		$application->school_id = $school->school_id;
		 
		return $application;
	}

	/*
	 * Old function handling miniature of the appplication
	* now deprecated as there is no miniature anymore
	*
	*
	protected function _copyMiniature($user, $application, $oldData)
	{
	$copy = TRUE;
	 
	$cachedMiniature = strstr($application->miniature, '/') ?
	substr($application->miniature, strpos($application->miniature, '/') + 1) :
	$application->miniature;

	if ($oldData != null)
	{
	//no new miniature has been sent
	if ($oldData->miniature == $cachedMiniature)
	$copy = FALSE;
	}
	 
	if ($copy)
	{
	$options = Zend_Registry::get('options');
	$extension = Zefir_Filter::getExtension($application->miniature);
	$dirName = APPLICATION_PATH.'/../public'.$options['upload']['miniatures'].'/';

	if ($oldData == null)
	{//get new name only if new application is being processed
	$fileName = $user->getUserUrlName().'.'.$extension;
	$fileName = $this->_getNewName($dirName , $fileName);
	}
	else
	$fileName = $oldData->miniature;
	$dir = substr($options['upload']['miniatures'], -1) == '/' ? $options['upload']['miniatures'] : $options['upload']['miniatures'].'/';


	if ($this->_copy($cachedMiniature, $dir.$fileName))
	{
	$application->miniature = $fileName;
	}
	else
	{
	if ($oldData != null)
	{//delete data only after failing add new application
	$user->delete();
	throw new Zend_Exception('Couldn\'t save miniature file');
	}
	}
	}
	else
	{
	$application->miniature = substr($application->miniature, strpos($application->miniature, '/') + 1);
	}
	 
	return $application;
	}
	*/

	protected function _getUserDir($application, $user, $oldData)
	{
		$id = $application->application_id;
		$options = Zend_Registry::get('options');
		 
		//create folder for user's files
		$uploadDir = APPLICATION_PATH.'/../public'.$options['upload']['applications'].'/';
		 
		//create edition dir
		$edition = new Application_Model_Editions($application->edition_id);
		$editionName = $edition->edition_name;
		if (!is_dir($uploadDir.$editionName))
		{
			mkdir($uploadDir.$editionName );
			chmod($uploadDir.$editionName, 0777);
		}
		 
		$uploadDir = $uploadDir.$editionName;
		 
		$type = new Application_Model_WorkTypes($application->work_type_id);
		 
		$userDir = strtoupper($application->country).'_'.$type->work_type_name.'_'.$application->user->getUserUrlName().'_'.$id;
		if (!is_dir($uploadDir.'/'.$userDir) && $oldData == null)
		{
			//create new user dir
			mkdir($uploadDir.'/'.$userDir);
			chmod($uploadDir.'/'.$userDir, 0777);
		}
		elseif ($oldData != NULL)
		{
			//rename old dir if necessary
			$oldDir = $this->_getOldUserDir($oldData);

			if ($userDir != $oldDir)
			{
				$this->_renameOldUserDir($oldDir, $userDir, $editionName);
			}
		}
		 
		return $editionName.'/'.$userDir;
	}

	protected function _getOldUserDir($application)
	{
		$file_path = $application->files[0]->path;
		$strippedEdition = substr($file_path, strpos($file_path, '/')+1);
		$dir = substr($strippedEdition, 0, strpos($strippedEdition, '/'));
		return $dir;
	}

	protected function _renameOldUserDir($oldDir, $newDir, $edition)
	{
		$options = Zend_Registry::get('options');
		$dirPath = APPLICATION_PATH.'/../public'.$options['upload']['applications'].'/'.$edition.'/';

		rename($dirPath.$oldDir, $dirPath.$newDir);
	}

	protected function _saveUserFiles($application, $user, $userDir, $oldData)
	{
		$options = Zend_Registry::get('options');
		$files = array();

		foreach($application->files as $key => $uploaded_file)
		{
			if (strstr($uploaded_file['file'], 'cache'))
			{
				$uploaded_file['file'] = substr($uploaded_file['file'], strpos($uploaded_file['file'], '/') + 1);
				$fileName = 'file.'.strtolower(Zefir_Filter::getExtension($uploaded_file['file']));
				$fileName = $this->_getNewName(APPLICATION_PATH.'/../public'.$options['upload']['applications'].'/'.$userDir.'/', $fileName);

				if ($this->_copy($uploaded_file['file'], $options['upload']['applications'].'/'.$userDir.'/'.$fileName))
				{
					if ($uploaded_file['file_id'] != null)
						$file = new Application_Model_Files($uploaded_file['file_id']);
					else
						$file = new Application_Model_Files();
						
					$file->path = $userDir.'/'.$fileName;
					$file->application_id = $application->application_id;
					$file->save();
					$files[] = $file;
				}
				else
				{
					if ($oldData == null)
					{
						//delete new user
						$user->delete();
						 
						//delete files and user directory
						$this->_deleteApplicationFiles($files);
						$uploadDir = APPLICATION_PATH.'/..public/'.$options['upload']['applications'].'/';
						unlink($uploadDir.$userDir);
						 
						//delete application entry
						$application->delete();
					}
					//throw error
					throw new Zend_Exception('Couldn\'t copy pictures');
				}
					
			}
			else
			{//update path if necessary
				if (!strstr($uploaded_file['file'], $userDir))
				{
					$file = new Application_Model_Files($uploaded_file['file_id']);
					$path = substr($file->path, 0, strrpos($file->path, '/'));

					$file->path = str_replace($path, $userDir, $file->path);
					$file->save();
					$files[] = $file;
				}
			}

		}
		 
		return $files;
	}

	protected function _deleteApplicationFiles($files)
	{
		$options = Zend_Registry::get('options');
		foreach($files as $file)
		{
			$dir = APPLICATION_PATH.'/../public'.$options['upload']['applications'].'/'.substr($file->path, 0, strrpos($file->path, '/'));
			$path = APPLICATION_PATH.'/../public'.$options['upload']['applications'].'/'.$file->path;
			//unlink($path);
			$file->delete();
		}
		 
		//rmdir($dir);
	}
	
	public function getAdjacentApplication($application)
	{
			
		$adjacentApplication['previous'] = $this->_getPreviousApplication($application);
		$adjacentApplication['next'] = $this->_getNextApplication($application);
		
		return $adjacentApplication;
	}
	
	protected function _getPreviousApplication($application)
	{
		$select = $this->getAdapter()->query(
	    		'SELECT * FROM (
	    			(
	    			SELECT * FROM users 
	    				WHERE (users.surname < ? ) AND users.role = "user" 
	    				ORDER BY surname DESC, name DESC, user_id DESC 
	    				LIMIT 1
	    			) 
	    			UNION 
	    			
	    			(
	    			SELECT * FROM users
	    			WHERE surname > ?  AND users.role = "user"
	    			ORDER BY surname DESC, name DESC, user_id DESC
	    			LIMIT 1
	    			)
	    		) as previous',
		array($application->user->surname, $application->user->surname)
		);
			
		$row = $select->fetch();
		$applicationClass = get_class($application);
		$user = new Application_Model_Users();
		$user->populate($row);
		
		$previousApplication = $user->applications[0];
		return $previousApplication;
	}
	
	protected function _getNextApplication($application)
	{
		$select = $this->getAdapter()->query(
	    		'SELECT * FROM (
	    			(
	    			SELECT * FROM users 
	    				WHERE surname > ? AND users.role = "user"
	    				ORDER BY surname ASC, name ASC, user_id ASC
	    				LIMIT 1
	    			) 
	    			UNION 
	    			
	    			(
	    			SELECT * FROM users 
	    				WHERE surname < ? AND users.role = "user"
	    				ORDER BY surname ASC, name ASC, user_id ASC
	    				LIMIT 1
	    			)
	    		) as next',
		array($application->user->surname, $application->user->surname)
		);
			
		
		$row = $select->fetch();
		$applicationClass = get_class($application);
		$user = new Application_Model_Users();
		$user->populate($row);
		
		$nextApplication = $user->applications[0];
		return $nextApplication;
	}
}

