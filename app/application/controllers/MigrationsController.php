<?php

class MigrationsController extends Zefir_Controller_Action
{

    public function init()
    {
        parent::init();
    }

    public function indexAction()
    {
    	$lang = new Application_Model_Languages();
		$lang->findLang($this->view->lang);
		
		
    	$config = new Zend_Config(
			array(
		        'database' => array(
		            'adapter' => 'Pdo_Mysql',
		            'params'  => array(
		                'host'     => 'localhost',
		                'username' => 'my_4368a',
		    			'password' => 'pw4jKCwXrdrkcuRz8oyWqBXaCV',
		    			'dbname'   => 'my_4368',
		    			'charset'  => 'utf8'
		            )
		        )
		    )
		);
    	$db = Zend_Db::factory($config->database);
				
		$dyplomy_fields = array(
			//'_dyplomy_edition_bg', 
			//'_dyplomy_edition_coorganizer',
			//'_dyplomy_edition_excerpt',
			//'_dyplomy_edition_gadget',
			//'_dyplomy_edition_info',
			//'_dyplomy_edition_partners',
			//'_dyplomy_edition_title',
			'_dyplomy_email' => 'email',
			'_dyplomy_name' => 'name',
			//'_dyplomy_name2',
			'_dyplomy_promoter' => 'supervisor',
			//'_dyplomy_promoter2',
			'_dyplomy_promoter_title' => 'supervisor_degree',
			//'_dyplomy_promoter_title2',
			'_dyplomy_school' => array('fields' => 'school'),
			'_dyplomy_surname' => 'surname',
			//'_dyplomy_surname2',
			'_dyplomy_title' => array ('fields' => 'work_subject'),
			'_dyplomy_type' => array('degree' => 'degree_name'),
			//'_dyplomy_www' => 'webpage',
			//'_dyplomy_year_background'
			'post_content' => array('fields' => 'work_desc')
			);
		$degrees = array('magisterski' => 'M.A.', 'licencjacki' => 'B.A.');
		$degree = new Application_Model_Degrees();
		$editions = new Application_Model_Editions();
		
		$stmt = $db->query('SELECT DISTINCT post_id FROM wp_postmeta WHERE meta_key LIKE "_dyplomy%"');
		
		//$row['post_id'] = 230;
		while ($row = $stmt->fetch())
		{
			$stmt2 = $db->query('SELECT * FROM wp_posts WP JOIN wp_postmeta WM ON (WP.ID=WM.post_id ) WHERE WP.ID = '.$row['post_id']);
			
			$Diploma = new Application_Model_Diplomas();
			$Diploma->country = 'pl';
			
			//add diploma data
			while ($row_d = $stmt2->fetch())
			{
				if (isset($dyplomy_fields[$row_d['meta_key']]) && !is_array($dyplomy_fields[$row_d['meta_key']]))
				{
					$Diploma->$dyplomy_fields[$row_d['meta_key']] = trim($row_d['meta_value']);
				}
				elseif (isset($dyplomy_fields[$row_d['meta_key']]) && is_array($dyplomy_fields[$row_d['meta_key']]))
				{
					if (isset($dyplomy_fields[$row_d['meta_key']]['fields']))
					{
						$field = new Application_Model_Fields();
						$field->getField($dyplomy_fields[$row_d['meta_key']]['fields']);
						
						$DiplomaField = new Application_Model_DiplomaFields();
						$DiplomaField->lang_id = $lang->lang_id;
						$DiplomaField->field_id = $field->field_id;
						$DiplomaField->entry = trim($row_d['meta_value']);
						
						$Diploma->addChild($DiplomaField, 'fields');
					}
					if (isset($dyplomy_fields[$row_d['meta_key']]['degree']))
					{
						if (preg_match('/^magi.+/', $row_d['meta_value']))
							$dyp = 'magisterski';
						elseif (preg_match('/^licen.+/', $row_d['meta_value']))
							$dyp = 'licencjacki';
						else 
							$dyp = NULL;
						if ($dyp != null)
						{
							$d = $degree->findDegree($degrees[$dyp]);
							$Diploma->degree_id = $d->degree_id;
						}
					}
				}
				$saved_row = $row_d;
			}
			

			//add work description
			$field = new Application_Model_Fields();
			$field->getField($dyplomy_fields['post_content']['fields']);
			
			$DiplomaField = new Application_Model_DiplomaFields();
			$DiplomaField->lang_id = $lang->lang_id;
			$DiplomaField->field_id = $field->field_id;
			$DiplomaField->entry = trim($saved_row ['post_content']);
				
			$Diploma->addChild($DiplomaField, 'fields');
			
			//get edition
			$sql = $db->query('SELECT name FROM wp_terms WHERE term_id = 
				(SELECT term_id FROM wp_term_taxonomy WHERE term_taxonomy_id = 
					(SELECT term_taxonomy_id FROM wp_term_relationships WHERE object_id = '.$row['post_id'].' LIMIT 1)
				)');
			$r = $sql->fetch();
			$Diploma_edition = $editions->getEdition($r['name'], TRUE);
			$Diploma->edition_id = $Diploma_edition->edition_id;
			
			
			//get files(aka images)
			$folder = '/home/zefiryn/public_html/projekty/';
			
			$sim = $db->query('SELECT * FROM wp_posts WHERE post_type = "attachment" AND post_parent = '.$row['post_id']);
			
			if ($Diploma->surname != null)
			{
				var_dump($Diploma->surname);
				var_dump($sim->rowCount());
			}
			while ($rim = $sim->fetch())
			{
				$path = substr($rim['guid'], strlen('http://www.dyplomyprojektowe.pl/'));
				$source = $folder . $path;
				
				$destination = dirname(__FILE__).'/../../public/assets/editions/';
				$edition_folder = str_replace('/', '-', $Diploma_edition->edition_name);
				$user_folder = Zefir_Filter::strToUrl($Diploma->surname.'_'.$Diploma->name);
				
				//create edition folder
				$dest = $destination.$edition_folder;
				if (!is_dir($dest))
					mkdir($dest);
				
				//create user folder
				$dest = $dest.'/'.$user_folder;
				if (!is_dir($dest))
					mkdir($dest);
					
				$file = substr($source, strrpos($source, '/'));
				$dest .= $file;
				
				if ($Diploma->surname != null && copy($source, $dest))
				{
					$DiplomaFile = new Application_Model_DiplomaFiles();
					$DiplomaFile->path = $edition_folder.'/'.$user_folder.$file;
					$Diploma->addChild($DiplomaFile , 'files');
				}
				
			}
			
			if ($Diploma->surname != null)
			{
				$Diplomas[$row['post_id']] = array($row['post_id'] => $Diploma);
				$Diploma->save();
			}
		}
		
		$d = $Diplomas[230][230];
		//var_dump($d->files);
		
		
    }
    
    


}

