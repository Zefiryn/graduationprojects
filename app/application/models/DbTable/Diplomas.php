<?php

class Application_Model_DbTable_Diplomas extends Zefir_Application_Model_DbTable
{

	/**
	 * Name of the table without prefix
	 * @var string
	 */
    protected $_raw_name = 'diplomas';
    
     /**
     * Name of the table generated by the constructor
     * @var string
     */
    protected $_name = '';
    
    /**
	 * Primary key of the table
	 * @var string
	 */
    protected $_primary = 'diploma_id';


    /**
     * An array of child tables information
     * @var array
     */
     protected $_belongsTo = array(
    
    	'edition' => array(
    		'model' => 'Application_Model_Editions',
    		'column' => 'edition_id',
			'refColumn' => 'edition_id'
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
		)
    );
	
	/**
	 * An array of parent table information
	 * @var array
	 */
	protected $_hasMany = array(
		'files' => array(
			'model' => 'Application_Model_DiplomaFiles',
			'refColumn' => 'diploma_id',
			'order' => 'position'
		),
		
		'fields' => array(
			'model' => 'Application_Model_DiplomaFields',
			'refColumn' => 'diploma_id',
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
    
    public function getAdjacentDiplomas($diploma)
    {
    	
    	$adjacentDiplomas['previous'] = $this->_getPreviousDiploma($diploma);
    	$adjacentDiplomas['next'] = $this->_getNextDiploma($diploma);
    	
    	return $adjacentDiplomas;
    }
    
    protected function _getPreviousDiploma($diploma)
    {
    	$select = $this->getAdapter()->query(
    		'SELECT * FROM (
    			(
    			SELECT `diplomas`.* FROM `diplomas` 
    				WHERE (edition_id = ? ) AND (surname < ? ) 
    				ORDER BY `surname` DESC, `name` DESC 
    				LIMIT 1
    			) 
    			UNION 
    			
    			(
    			SELECT `diplomas`.* FROM `diplomas` 
    			WHERE (edition_id = ? ) AND (surname > ? ) 
    			ORDER BY `surname` DESC, `name` DESC 
    			LIMIT 1
    			)
    		) as previous',
    		array($diploma->edition_id, $diploma->surname, $diploma->edition_id, $diploma->surname)
    	);
    	

    	$row = $select->fetch();
    	$diplomaClass = get_class($diploma);
    	$previousDiploma = new $diplomaClass($row['diploma_id']);
    	
    	return $previousDiploma;
    }
    
	protected function _getNextDiploma($diploma)
    {
    	$select = $this->getAdapter()->query(
    		'SELECT * FROM (
    			(
    			SELECT `diplomas`.* FROM `diplomas` 
    				WHERE (edition_id = ? ) AND (surname > ? ) 
    				ORDER BY `surname` ASC, `name` ASC
    				LIMIT 1
    			) 
    			UNION 
    			
    			(
    			SELECT `diplomas`.* FROM `diplomas` 
    			WHERE (edition_id = ? ) AND (surname < ? ) 
    			ORDER BY `surname` ASC, `name` ASC 
    			LIMIT 1
    			)
    		) as next',
    		array($diploma->edition_id, $diploma->surname, $diploma->edition_id, $diploma->surname)
    	);
    	

    	$row = $select->fetch();
    	$diplomaClass = get_class($diploma);
    	$nextDiploma = new $diplomaClass($row['diploma_id']);
    	
    	return $nextDiploma;
    }
    
}

