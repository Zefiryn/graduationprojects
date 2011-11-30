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

	public function lastAction()
	{
		$config = new Zend_Config(
		array(
		        'database' => array(
		            'adapter' => 'Pdo_Mysql',
		            'params'  => array(
		                'host'     => 'localhost',
		                'username' => 'artur',
		    			'password' => 'artur1',
		    			'dbname'   => '2_3d',
		    			'charset'  => 'utf8'
		)
		)
		)
		);
		$db = Zend_Db::factory($config->database);
		 
		$dyplomy_fields = array(
			'competitor_email' => 'email',
			'competitor_name' => 'name',
    		'competitor_surname' => 'surname',
			'competitor_supervisor' => 'supervisor',
			'competitor_supervisor_degree' => 'supervisor_degree',
			'competitor_school' => array('fields' => 'school'),
    		'competitor_department' => array('fields' => 'department'),
			'competitor_work_subject' => array ('fields' => 'work_subject'),
			'competitor_school_level' => array('degrees' => 'degree_name'),
    		'graduation_time' => 'graduation_time',
			'country' => 'country'
		);
		$degrees = array('graduate' => 'M.A.', 'undergraduate' => 'B.A.');
		$degree = new Application_Model_Degrees();
		$type = new Application_Model_WorkTypes();
		$lang = new Application_Model_Languages();
		 
		$stmt = $db->query('SELECT * FROM applications WHERE publication_qualified = 1 or review_qualified = 1 order by competitor_work_type asc, competitor_surname asc, competitor_name asc');

		while ($row = $stmt->fetch())
		{
			$lang->findLangId(str_replace('cz', 'cs', $row['country']));
			$diploma = new Application_Model_Diplomas();
			$diploma->edition_id = 10;
			foreach($dyplomy_fields as $old => $new)
			{
				if (!is_array($new))
				$diploma->$new = $row[$old];
				else
				{
					if (isset($new['fields']))
					{
						$field = new Application_Model_Fields();
						$field->getField($new['fields']);

						$DiplomaField = new Application_Model_DiplomaFields();
						$DiplomaField->lang_id = $lang->lang_id;
						$DiplomaField->field_id = $field->field_id;
						$DiplomaField->entry = trim($row[$old]);

						$diploma->addChild($DiplomaField, 'fields');
					}
					else
					{
						$d = $degree->findDegree($degrees[$row[$old]]);
						$diploma->degree_id = $d->degree_id;
					}
				}
			}
			$type = $type->findWorkType($row['competitor_work_type']);
			$diploma->work_type_id = $type->work_type_id;
				
			//get files(aka images)
			$folder = '/home/zefiryn/public_html/2_3d/upload/';
				
			$stmt2 = $db->query('SELECT path from files where applications_application_id = '.$row['application_id']);
			while($rowf = $stmt2->fetch())
			{
				if (!strstr($rowf['path'], '.pdf'))
				{
					$source = $folder . $rowf['path'];
					$destination = dirname(__FILE__).'/../../public/assets/editions/';
					$edition_folder = '2009-2010';
					$user_folder = Zefir_Filter::strToUrl($diploma->surname.'_'.$diploma->name);
						
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
						
					if ($diploma->surname != null && copy($source, $dest))
					{
						$diplomaFile = new Application_Model_DiplomaFiles();
						$diplomaFile->path = $edition_folder.'/'.$user_folder.$file;
						$diploma->addChild($diplomaFile , 'files');
					}
				}
			}
				
			$diploma->save();
		}

		$this->render('index');
	}

	public function locAction()
	{

		$lang_sk = array(
			'lang' => 'sk',					//this should not be changed
			'meta_title' => 'Medzinárodná prehliadka absolventských prác',
			'yes' => 'tak',
			'no' => 'nie',
			'db_error' => 'Chyba spojenia s databázou',
			'regulation_link' => 'Pravidlá',
			'form_link' => 'Prihlásiť práce',
			'result_link' => 'Výsledky',
			
			
			
			
		//more text
			'about_header' => 'Medzinárodná prehliadka <br>absolventských prác',

		////faq
			'faq_header' => 'Často kladené otázky',

		//regulation
			'regulation_header' => 'Pravidlá',

		//result page
			'result_header' => 'Výsledky prehliadky',
			'early_result' => 'Výsledky ešte neboli vyhlásené',
			'result_info' => 'Práca zdôraznené v preskúmaní',
		//footer
			'creator' => 'Created by Zefiryn',
			'faq_link' => 'FAQ <!--Často kladené otázky-->',
			'contact_link' => 'Kontakt',
			'previous_link' => 'Predchádzajúca stránka',

		//entry form
			'entry_form' => 'Prihláška',
			'country' => 'Štát',
			'pl' => 'Poľsko',
			'sk' => 'Slovensko',
			'cz' => 'Česká republika',
			'user_name' => 'Meno',
			'user_surname' => 'Priezvisko',
			'address' => 'Adresa',
			'phone' => 'Telefón',
			'phone_format' => 'Telefónne číslo zadajte vo formáte +421 12345678',
			'email' => 'E-mail',
			'email_check' => 'Potvrdiť e-mail',
			'show_email' => 'Prosím o uvedenie e-mailovej adresy u prezentovanej práce',
			'show_email_display' => 'Zobrazovať u práce e-mail',
			'school' => 'Škola',
			'department' => 'Katedra',
			'workshop' => 'Ateliér',
			'school_level' => 'Druh štúdia',
			'school_level_expl' => '(vyberte)', 
			'undergraduate' => 'bakalárske',
			'graduate' => 'magisterské',
			'work_subject' => 'Téma práce',
			'work_short_subject' => 'Skrócony temat',
			'work_short_subject_explain' => 'Typ práce (napríklad plagát, kreslo, kniha)',
			'work_type' => '2D/3D',
			'work_desc' => 'Popis práce (max. 2000 znakov)',
			'counter' => 'Počet znakov',
			'supervisor' => 'Vedúci práce',
			'supervisor_degree' => 'Akademický titul vedúceho práce',
			'graduation' => 'Dátum obhajoby',
			'graduation_day' => 'Deň',
			'graduation_month' => 'Mesiac',
			'graduation_year' => 'Rok',
			'miniature' => 'Náhľad',
			'new_miniature' => 'Zmeniť náhľad',
			'miniature_explain' => 'Jeden obrázok, ktorý reprezentuje prácu, s rozmerom 800 × 800 px',
			'submit' => 'Odoslať prihlášku',
			'confirmation_header' => 'Przesłane dane',
			'confirm_submit' => 'Zadané údaje',
			'return_submit' => 'Potvrdiť prihlášku',
			'personal_data_agreement' => 'Súhlasím so spracovaním osobných údajov uvedených vo formulári za účelom zorganizovania prehliadky, v zmysle zákona Poľskej republiky na ochranu osobných údajov zo dňa 29.8.1997. (Dz. U. z 2002 r. nr 101, poz. 926 ze zm.)',

		//confirmation mail
			'mail_confirm_subject' => 'Přihlášení práce do Mezinárodní přehlídky absolventských prací',
			'mail_confirm_text' => 'Toto je automatická zpráva vygenerovaná po úspešném přijetí přihlášky do projektu Mezinárodní přehlídky absolventských prací.',
			'mail_send_data' => 'Prenesených dát',
			'file_counter' => 'Počet súborov',
			
			'mail_confirm_success' => 'Na zadaný e-mail byla odeslána zpráva s potvrzením přihlášky',
			'mail_confirm_fail' => 'Během odesílání zprávy potvrzující přihlášku se objevila chyba',

		//other application columns
			'application_id' => 'ID',
			'edition' => 'Edícia',
			'graduation_time' => 'Dátum obhajoby',
			'application_date' => 'Dátum podania prihlášky',
			'jury_qualified' => 'Kkceptovaný na ......',
			'publication_qualified' => 'Vybrané na uverejnenie',
			'review_qualified' => 'Vybrané na uverejnenie',
			'annotations' => 'Poznámky',

		//files
			'application_files' => 'Súbory',
			'cached_files' => 'Nahrané súbory',
			'add_file' => 'Pridať súbor (JPG, PDF)',
			'file' => 'Súbor',
			'file_desc' => 'Popis ilustrácií',
			'file_max_ammount' => 'Maximálny počet prác',
			'file_max_size' => 'Maximálna veľkosť súboru',
			'remove' => 'Odstrániť',
			'upload_error' => 'Chyba nahrávania',


		//adding result
			'adding_result' => 'Prihlásenie projektu',
			'adding_error' => 'Počas prihlasovania projektu sa objavili chyby',
			'adding_success' => 'Prihláška bola úspešne uložená',
			'error' => 'Chyba',
			
		//contact
			'contact_header' => 'Kontakt',
			'mailer_name' => 'Meno a priezvisko',
			'mailer_mail' => 'E-mailová adresa',
			'mail_subject' => 'Názov správy',
			'mail_text' => 'Obsah správy',
			'mail_text_count' => '(max 5000 znakov)',
			'mail_submit' => 'Odoslať správu',
			'mail_success' => 'Správa bola úspešne odoslaná',
			'mail_fail' => 'Pri odosielaní správy došlo k chybe',
			'nomail' => 'Nie udało się załadować obsługi wysyłania wiadomości',
			'nomailconfig' => 'Chýba konfiguračný súbor',
			'mail_confirm' => 'Odoslať správu',
			'mail_return' => 'Upraviť správu',
			'from' => 'Od');

		$lang_cs = array(
			'meta_title' => 'Central European Review – Graduation Projects',
			'yes' => 'ano',
			'no' => 'ne',
			'db_error' => 'Chyba spojení s databází',
			
			'about_header' => 'Mezinárodní přehlídka <br>absolventských prací',
			
			
		//faq
			'faq_header' => 'Často kladené otázky',
		//regulation
			'regulation_header' => 'Pravidla',
		//result page
			'result_header' => 'Výsledky přehlídky',
			'early_result' => 'Výsledky ještě nebyly vyhlášeny',
			'result_info' => 'Práce zdůrazněno v přezkumu',
		//footer 
			'creator' => 'Created by Zefiryn',
			'regulation_link' => 'Pravidla',
			'form_link' => 'Přihlásit práce',
			'faq_link' => 'Často kladené otázky',
			'contact_link' => 'Kontakt',
			'result_link' => 'Výsledky',
			'previous_link' => 'Přechozí stránka',
			
			'error_info' => 'Chyba',
			'query' => 'Dotaz',
			'error_occured' => 'Došlo k chybě',

			
		//entry form
			'entry_form' => 'Přihláška',
			'before_filling_form' => 'Před vyplněním formuláře si prosím přečtěte pravidla a často kladené otázky.',
			'country' => 'Stát',
			'user_name' => 'Jméno',
			'user_surname' => 'Příjmení',
			'address' => 'Adresa',
			'phone' => 'Telefon',
			'phone_format' => 'Telefonní číslo zadejte ve formátu +420 12345678',
			'email' => 'E-mail',
			'email_check' => 'Potvrdit e-mail',
			'show_email' => 'Přeji si uvést e-mailovou adresu společně s  prací',
			'show_email_display' => 'Zobrazovat u práce e-mail',
			'school' => 'Škola',
			'department' => 'Katedra',
			'workshop' => 'Ateliér',
			'school_level' => 'Druh studia',
			'school_level_expl' => '(vyberte)', 
			'undergraduate' => 'bakalářské',
			'graduate' => 'magisterské',
			'work_subject' => 'Úplný název práce',
			'work_short_subject' => 'Stručný název práce',
			'work_short_subject_explain' => 'Typ práce (např. plakát, křeslo, knížka)',
			'work_type' => '2D/3D',
			'work_desc' => 'Popis práce (max. 2000 znaků)',
			'counter' => 'Počet znaků',
			'supervisor' => 'Vedoucí práce',
			'supervisor_degree' => 'Akademický titul vedoucího',
			'graduation' => 'Datum obhajoby',
			'graduation_day' => 'Den',
			'graduation_month' => 'Měsíc',
			'graduation_year' => 'Rok',
			'miniature' => 'Náhled',
			'new_miniature' => 'Změnit náhled',
			'miniature_explain' => 'Jeden obrázek, který reprezentuje práci, o rozměru 800 × 800 px',
			'submit' => 'Odeslat přihlášku',
			'confirmation_header' => 'Zadané údaje',
			'confirm_submit' => 'Potvrdit přihlášku',
			'return_submit' => 'Upravit údaje',
			'personal_data_agreement' => 'Souhlasím se zpracováním osobních údajů uvedených ve formuláři za účelem zorganizování přehlídky, ve smyslu zákona Polské republiky na ochranu osobních údajů ze dne 29.8.1997. (Dz. U. z 2002 r. nr 101, poz. 926 ze zm.)',
			
			
		//confirmation mail
			'mail_confirm_subject' => 'Přihlášení práce do Mezinárodní přehlídky absolventských prací',
			'mail_confirm_text' => 'Toto je automatická zpráva vygenerovaná po úspešném přijetí přihlášky do projektu Mezinárodní přehlídky absolventských prací.',
			'file_counter' => 'Počet souborů',
			'mail_send_data' => 'Přenesených dat',
			
			'mail_confirm_success' => 'Na zadaný e-mail byla odeslána zpráva s potvrzením přihlášky',
			'mail_confirm_fail' => 'Během odesílání zprávy potvrzující přihlášku se objevila chyba',
			
		//other application columns
			'application_id' => 'ID',
			'edition' => 'Edycja',
			'graduation_time' => 'Datum obhajoby',
			'application_date' => 'Datum podání přihlášky',
			'jury_qualified' => 'Zakwalifikowane do konkursu',
			'publication_qualified' => 'Vybráno k uveřejnění',
			'review_qualified' => 'Vybráno do přehlídky',
			'annotations' => 'Poznámky',
			
			
		//files
			'files' => 'Soubory',
			'cached_files' => 'Nahrané soubory',
			'add_file' => 'Přidat soubor (JPG, PDF)',
			'file' => 'Soubor',
			'file_desc' => 'Popis ilustrací',
			'file_max_ammount' => 'Maximální počet prací',
			'file_max_size' => 'Maximální velikost souboru',
			'remove' => 'Odstranit',
			'upload_error' => 'Chyba nahrávání',
		//adding result
			'adding_result' => 'Přihlášení projektu',
			'adding_error' => 'Během přihlašování projektu se objevily chyby',
			'adding_success' => 'Přihláška byla úspěšně přijata',
			'error' => 'Chyba',
			
			
		//contact
			'contact_header' => 'Kontakt',
			'mailer_name' => 'Jméno a příjmení',
			'mailer_mail' => 'E-mailová adresa',
			'mail_subject' => 'Název zprávy',
			'mail_text' => 'Obsah zprávy',
			'mail_text_count' => '(max. 5000 znaků)',
			'mail_submit' => 'Odeslat zprávu',
			'mail_success' => 'Zpráva byla úspěšně odeslána',
			'mail_fail' => 'Při odesílání zprávy došlo k chybě',
			'nomail' => 'Nepodařilo se nahrát modul pro odesílání zpráv',
			'nomailconfig' => 'Chybí konfigurační soubor',
			'mail_confirm' => 'Odeslat zprávu',
			'mail_return' => 'Upravit zprávu',
			'from' => 'Od',
			
			
		);

		$captions = new Application_Model_Captions();
			
		foreach($lang_sk as $caption => $translation)
		{
			$lang_id = 3;
			$cap_id = $captions->getCaptionId($caption);
			if ($cap_id)
			{
				$loc = new Application_Model_Localizations();
				$loc->caption_id = $cap_id;
				$loc->lang_id = $lang_id;
				$loc->text = $translation;
				$loc->save();
			}
		}
			
		foreach($lang_cs as $caption => $translation)
		{
			$lang_id = 2;
			$cap_id = $captions->getCaptionId($caption);
			if ($cap_id)
			{
				$loc = new Application_Model_Localizations();
				$loc->caption_id = $cap_id;
				$loc->lang_id = $lang_id;
				$loc->text = $translation;
				$loc->save();
			}
		}
			
		$this->render('index');
	}

	public function resizeAction()
	{
		$request = $this->getRequest();
		$id = $request->getParam('edition');

		$edition = new Application_Model_Editions($id);

		foreach($edition->diplomas as $diploma)
		{
			foreach ($diploma->files as $file)
			{
				$file->recreateThumbnails();
			}
		}
		$this->render('index');
	}

	public function infoAction()
	{
		$this->_helper->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
		phpinfo();
	}
}

