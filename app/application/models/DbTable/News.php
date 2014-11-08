<?php

class Application_Model_DbTable_News extends Zefir_Application_Model_DbTable {

    protected $_raw_name = 'news';
    protected $_name;
    protected $_primary = 'news_id';
    protected $_hasMany = array(
        'details' => array(
            'model' => 'Application_Model_NewsDetails',
            'refColumn' => 'news_id',
        ),
        'files' => array(
            'model' => 'Application_Model_NewsFiles',
            'refColumn' => 'news_id',
            'order' => 'position ASC',
        ),
    );

    public function getAll($args) {
        $select = $this->select()->order('added DESC');
        
        if ($args[1]) {
            $select->where('published = 1');
            $langSession = new Zend_Session_Namespace('lang');
            $lang = new Application_Model_Languages();
            $this->joinDetails($select)->where('details.lang_id = ?', $lang->findLangId($langSession->lang));
        }

        if ($args[0]) {
            $tplSettings = Zend_Registry::get('tplSettings');
            // set limit and offsett according to role 
            // admins has additional + at the beginning of the news list
            $limit = $args[0] * ($tplSettings->news_limit - ($args[1] ? 0 : 1));
            $offset = ($args[0] - 1) * ($tplSettings->news_limit - ($args[1] ? 0 : 1));
            $select->limit($limit, $offset);
        }

        return $this->fetchAll($select);
    }

    public function getAllInOrder($order, $unpublished = false) {
        $select = $this->select()->order($order);

        if (!$unpublished)
            $select = $select->where('published = 1');

        return $this->fetchAll($select);
    }

    public function joinDetails($select) {
        $joinModel = new $this->_hasMany['details']['model']();

        $select->from(array('main_table' => $this->getTableName()));
        $select->setIntegrityCheck(FALSE);
        $select->joinInner(array('details' => $joinModel->getDbTable()->getTableName()), 'details.' . $this->_hasMany['details']['refColumn'] . ' = main_table.' . $this->getPrimaryKey(), array());
        $select->group('main_table.' . $this->getPrimaryKey());

        return $select;
    }

}
