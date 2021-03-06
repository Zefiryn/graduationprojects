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
        'disputes' => array(
            'model' => 'Application_Model_Disputes',
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

    public function getAllApplications($sort, $stage, $type = null)
    {
        $user = new Application_Model_Users();
        $user_table = $user->getDbTable()->getTablename();

        $work_type = new Application_Model_WorkTypes();
        $work_type_table = $work_type->getDbTable()->getTablename();

        $appSettings = Zend_Registry::get('appSettings');
        $select = $this->select()
            ->setIntegrityCheck(FALSE)
            ->from(array('a' => $this->_name))
            ->join(array('u' => $user_table), 'a.user_id = u.user_id')
            ->join(array('w' => $work_type_table), 'a.work_type_id= w.work_type_id')
            ->where('edition_id = ?', $appSettings->current_edition)
            ->order($sort);

        if ($type != null && $type != 'all') {
            $select->where('w.work_type_name = ?', $type);
        }

        if ($stage) {
            if ($stage->order > 1) {
                $vote = new Application_Model_Votes();
                $select2 = $vote->getDbTable()
                    ->select()
                    ->from($vote->getDbTable()->getTableName(), 'application_id')
                    ->where('stage_id = ?', $stage->getPreviousStageId())
                    ->group('application_id')
                    ->having('SUM(vote) >= ?', $stage->qualification_score);

                $select->where('application_id IN (' . $select2->__toString() . ')');
            }

        }
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
        if ($application->application_id != null) {
            $oldData = new Application_Model_Applications($application->application_id);
        } else {
            $oldData = null;
        }

        //add new school if any was given
        $application = $this->_addNewSchool($application);

        //check if there is user added
        if ($application->user_id == null && isset($application->user))
            $application->user_id = $application->user->user_id;

        //save application data
        try {
            //start transaction - until all files are saved
            //$transaction = $this->getAdapter()->beginTransaction();

            $application = parent::save($application);
            $application->user = new Application_Model_Users($application->user_id);
            //create user directory
            $userDir = $this->_getUserDir($application, $application->user, $oldData);

            //copy uploaded files
            $files = $this->_saveUserFiles($application, $application->user, $userDir, $oldData);

            //commit transaction
            //$transaction->commit();

        } catch (Zend_Exception $e) {

            //roll back
            //$transaction->rollBack();

            //delete files
            $this->_deleteApplicationFiles($files);
            throw $e;
        }

        //$application->files = $files;

        return $application;

    }

    public function delete(Application_Model_Applications $application)
    {
        $options = Zend_Registry::get('options');

        //remove files
        $this->_deleteApplicationFiles($application->files);

        //remove miniature
        $path = APPLICATION_PATH . '/../public' . $options['upload']['miniatures'] . '/' . $application->miniature;
        unlink($path);

        //remove user
        $application->user->delete();

        //remove application
        parent::delete($application);
    }

    public function countVotes($stage, $app)
    {
        $select = $this->select()
            ->from($this->_prefix . 'votes', array('SUM(vote) as votes'))
            ->setIntegrityCheck(false)
            ->where('application_id = ?', $app)
            ->where('stage_id = ?', $stage);
        $row = $this->fetchRow($select);

        return $row['votes'];
    }

    public function getRemainedApps($apps_keys)
    {
        if (count($apps_keys) > 0) {
            $select = $this->select()->setIntegrityCheck(false)
                ->from(array('a' => $this->getTableName()))
                ->join(array('u' => 'users'), 'a.user_id = u.user_id')
                ->where('application_id NOT IN (?)', $apps_keys)
                ->order(array('u.surname', 'u.name'));
            return parent::fetchAll($select);
        } else {
            return array();
        }
    }

    protected function _addNewSchool($application)
    {
        $school = new Application_Model_Schools();
        $school->getSchoolByName($application->school->school_name);

        if ($school->school_id == NULL) {
            $school->school_name = $application->school->school_name;
            $school->save();
            if ($school->school_id == null)
                throw new Zend_Exception('Couldn\'t add new school');
        }
        $application->school = $school;
        $application->school_id = $school->school_id;

        return $application;
    }


    protected function _getUserDir($application, $user, $oldData)
    {
        $id = $application->application_id;
        $options = Zend_Registry::get('options');

        //create folder for user's files
        $uploadDir = APPLICATION_PATH . '/../public' . $options['upload']['applications'] . '/';

        //create edition dir
        $edition = new Application_Model_Editions($application->edition_id);
        $editionName = $edition->edition_name;
        if (!is_dir($uploadDir . $editionName)) {
            mkdir($uploadDir . $editionName);
            chmod($uploadDir . $editionName, 0777);
        }

        $uploadDir = $uploadDir . $editionName;

        $type = new Application_Model_WorkTypes($application->work_type_id);

        $userDir = strtoupper($application->country) . '_' . $type->work_type_name . '_' . $application->user->getUserUrlName() . '_' . $id;
        if (!is_dir($uploadDir . '/' . $userDir) && ($oldData == null || !$this->_getOldUserDir($oldData))) {
            //create new user dir
            mkdir($uploadDir . '/' . $userDir);
            chmod($uploadDir . '/' . $userDir, 0777);
        } elseif ($oldData != NULL) {
            //rename old dir if necessary
            $oldDir = $this->_getOldUserDir($oldData);

            if ($userDir != $oldDir) {
                $this->_renameOldUserDir($oldDir, $userDir, $editionName);
            }
        }

        return $editionName . '/' . $userDir;
    }

    protected function _getOldUserDir($application)
    {
        $file_path = $application->files[0]->path;
        $strippedEdition = substr($file_path, strpos($file_path, '/') + 1);
        $dir = substr($strippedEdition, 0, strpos($strippedEdition, '/'));
        return $dir;
    }

    protected function _renameOldUserDir($oldDir, $newDir, $edition)
    {
        $options = Zend_Registry::get('options');
        $dirPath = APPLICATION_PATH . '/../public' . $options['upload']['applications'] . '/' . $edition . '/';

        rename($dirPath . $oldDir, $dirPath . $newDir);
    }

    protected function _saveUserFiles($application, $user, $userDir, $oldData)
    {
        $options = Zend_Registry::get('options');
        $files = array();

        foreach ($application->files as $key => $uploaded_file) {
            if (strstr($uploaded_file['file'], 'cache')) {
                $uploaded_file['file'] = substr($uploaded_file['file'], strpos($uploaded_file['file'], '/') + 1);
                $fileName = 'file.' . strtolower(Zefir_Filter::getExtension($uploaded_file['file']));
                $fileName = $this->_getNewName(APPLICATION_PATH . '/../public' . $options['upload']['applications'] . '/' . $userDir . '/', $fileName);

                if ($this->_copy($uploaded_file['file'], $options['upload']['applications'] . '/' . $userDir . '/' . $fileName)) {
                    if ($uploaded_file['file_id'] != null)
                        $file = new Application_Model_Files($uploaded_file['file_id']);
                    else
                        $file = new Application_Model_Files();

                    $file->path = $userDir . '/' . $fileName;
                    $file->application_id = $application->application_id;
                    $file->save();
                    $files[] = $file;
                } else {
                    if ($oldData == null) {
                        //delete new user
                        $user->delete();

                        //delete files and user directory
                        $this->_deleteApplicationFiles($files);
                        $uploadDir = APPLICATION_PATH . '/..public/' . $options['upload']['applications'] . '/';
                        unlink($uploadDir . $userDir);

                        //delete application entry
                        $application->delete();
                    }
                    //throw error
                    throw new Zend_Exception('Couldn\'t copy pictures');
                }

            } else {//update path if necessary
                if (!strstr($uploaded_file['file'], $userDir)) {
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
        foreach ($files as $file) {
            $dir = APPLICATION_PATH . '/../public' . $options['upload']['applications'] . '/' . substr($file->path, 0, strrpos($file->path, '/'));
            $path = APPLICATION_PATH . '/../public' . $options['upload']['applications'] . '/' . $file->path;
            unlink($path);
            $file->delete();
        }

        rmdir($dir);
    }

    public function getAdjacentApplication($application)
    {
        $sortedApps = new Zend_Session_Namespace('sortedApps');
        if (!$sortedApps->keys) {
            $sort = new Zend_Session_Namespace('app_sort');
            if (!$sort->sort) $sort->sort = 'surname';

            $sortOrder = new Zend_Session_Namespace('sort_order');
            if (!$sortOrder->order) $sortOrder->order = 'ASC';

            $sort = strstr($sort->sort, 'work_type_id') ? 'a.' . $sort->sort : $sort->sort;
            $sort = $sort != NULL ? array($sort . ' ' . $sortOrder->order, 'surname ASC', 'name ASC', 'application_date ASC') : array('application_date ASC', 'surname ASC');

            $appsModel = new Application_Model_Applications();
            $apps = $appsModel->getApplications();

            $sortedApps->keys = array_keys($apps);
        }

        $adjacentApplication['previous'] = $this->_getPreviousApplication($sortedApps->keys, $application);
        $adjacentApplication['next'] = $this->_getNextApplication($sortedApps->keys, $application);

        return $adjacentApplication;
    }

    protected function _getPreviousApplication($apps, $application)
    {
        $order = array_flip($apps);
        if (isset($order[$application->application_id]) && $order[$application->application_id] != 0) {
            $prevId = $order[$application->application_id] - 1;
        } else {
            $prevId = count($apps) - 1;
        }
        $prev = new Application_Model_Applications($apps[$prevId]);

        return $prev;
    }

    protected function _getNextApplication($apps, $application)
    {

        $order = array_flip($apps);
        if (isset($order[$application->application_id]) && $order[$application->application_id] + 1 < count($apps)) {
            $nextId = $order[$application->application_id] + 1;
        } else {
            $nextId = 0;
        }
        $next = new Application_Model_Applications($apps[$nextId]);

        return $next;
    }
}

