DROP TABLE localizations;
CREATE TABLE localizations (
	item_id int NOT NULL AUTO_INCREMENT,
	name varchar(60) not null,
	lang_code char(4) not null,
	text text not null,
	PRIMARY KEY (item_id)
) ENGINE=INNODB DEFAULT CHARSET=utf8;

INSERT INTO localizations VALUES
	(null,'Partners','pl','Partnerzy'),
	(null,'meta_title','pl','Międzynarodowy przegląd projektów dyplomowych'),
	(null,'main_page_header','pl','Międzynarodowy <br>przegląd <br>projektów <br>dyplomowych'),
	(null,'main_page_text','pl','Praca dyplomowa – tak magisterska, jak licencjacka – jest szczególnym momentem w&nbsp;życiu projektanta. Z jednej strony to zamknięcie, podsumowanie czasu studiów, cieplarnianej jednak sytuacji pracy pod bacznym okiem fachowców, wykładowców i profesorów, z&nbsp;drugiej strony, dla wielu to pierwszy projekt prezentowany w portfolio i początek pracy projektowej prowadzonej już na własny rachunek i odpowiedzialność. W 2002 roku redakcja „2+3D” ogłosiła pierwszy przegląd projektów dyplomowych, obronionych na polskich uczelniach projektowych. W tym roku, wraz z&nbsp;czeskim magazynem „Typo” i&nbsp;słowackim „Designum”, bazując na ciekawej, liczącej już kilka lat współpracy przy projekcie wydawnictw G4, postanowiliśmy nadać kolejnej edycji status przeglądu międzynarodowego”. <br><br>\r\nZaprszamy do udziału w przeglądzie! '),
	(null,'main_page_more_link','pl','więcej'),
	(null,'regulation_link','pl','Regulamin'),
	(null,'form_link','pl','Zgłoś pracę'),
	(null,'result_link','pl','Wyniki'),
	(null,'Partners','cs','Partneři'),
	(null,'Partners','sk','Partneri'),
	(null,'meta_title','cs','Central European Review – Graduation Projects'),
	(null,'meta_title','sk','Medzinárodná prehliadka absolventských prác'),
	(null,'main_page_header','cs','Mezinárodní <br>přehlídka <br>absolventských <br>prací'),
	(null,'main_page_text','cs','Absolventská práce, ať už magisterská nebo bakalářská, je výrazným momentem v životě designéra. Na jedné straně končí období studia, kdy práce vznikají pod dohledem odborníků, lektorů a profesorů, na straně druhé to znamená začátek samostatné designérské kariéry se vší odpovědností. V roce 2002 zorganizovali redaktoři polského čtvrtletníku 2+3D první přehlídku diplomových prací obhájených na polských katedrách designu. Letos je akce poprvé&nbsp;organizována jako mezinárodní projekt, na kterém s polským časopisem 2+3D spolupracuje český časopis TYPO a slovenský magazín Designum. Organizátoři navázali na úspěšnou spolupráci na projektu G4, který mapuje dění v&nbsp;oblasti designu v zemích visegrádské čtyřky. <br><br>Zveme vás k účasti na přehlídce!'),
	(null,'main_page_more_link','cs','více'),
	(null,'main_page_header','sk','Medzinárodná <br>prehliadka <br>absolventských <br>prác'),
	(null,'main_page_text','sk','Absolventská práca, či už magisterská alebo bakalárska, je výrazným momentom v živote dizajnéra. Na jednej strane končí obdobie štúdia, kedy jeho práce vznikajú pod dohľadom odborníkov, lektorov a profesorov, na druhej strane to znamená začiatok samostatnej dizajnérskej kariéry so všetkou zodpovednosťou. V roku 2002 zorganizovali redaktori poľského štvrťročného periodika 2+3D prvú prehliadku diplomových prác obhájených na poľských katedrách dizajnu. Tento rok je akcia prvýkrát organizovaná ako medzinárodný projekt, na&nbsp;ktorom spolupracuje český časopis TYPO a&nbsp;slovenský magazín Designum. Organizátori nadviazali na úspešnú spoluprácu na projekte G4, ktorý mapuje dianie v oblasti dizajnu v krajinách vyšehradskej štvorky. <br><br>Pozývame vás zúčastniť sa na prehliadke!'),(null,'main_page_more_link','sk','viac'),
	(null,'regulation_link','cs','Pravidla'),(null,'form_link','cs','Přihlásit práce'),
	(null,'result_link','cs','Výsledky'),(null,'regulation_link','sk','Pravidlá'),
	(null,'form_link','sk','Prihlásiť práce'),(null,'result_link','sk','Výsledky'),
	(null,'contact_link','cs','Kontakt'),(null,'contact_link','pl','Kontakt'),
	(null,'contact_link','sk','Kontakt'),
	(null,'footer_visegrad','pl','Projekt wydany dzięki pomocy finansowej Międzynarodowej Fundacji Wyszehradzkiej www.visegrad.eu'),
	(null,'footer_visegrad','cs','Projekt finančně podpořil Mezinárodní visegradský fond www.visegradfund.org'),
	(null,'footer_visegrad','sk','Projekt bol finančne podporený Medzinárodným vyšehradským fondom www.visegradfund.org'),
	(null,'about_text','pl','Praca dyplomowa – tak magisterska, jak licencjacka – jest szczególnym momentem w życiu projektanta. Z jednej strony to zamknięcie, podsumowanie czasu studiów, cieplarnianej jednak sytuacji pracy pod bacznym okiem fachowców, wykładowców i profesorów, z drugiej strony, dla wielu to pierwszy projekt prezentowany w portfolio i początek pracy projektowej prowadzonej już na własny rachunek i odpowiedzialność. W 2002 roku redakcja kwartalnika „2+3D” ogłosiła pierwszy przegląd projektów dyplomowych, obronionych na polskich uczelniach projektowych, na który nadesłano 45 prac (22 z zakresu grafiki użytkowej i 23 z wzornictwa przemysłowego). Rokrocznie liczba ta zwiększała się i do ostatniej edycji zgłoszono już 149 projektów (93 z zakresu grafiki użytkowej grafiki i 56 z wzornictwa przemysłowego), co może świadczyć o szerokiej akceptacji tego pomysłu i docenieniu wartości publikacji wybranych projektów na łamach kwartalnika. Przegląd od samego początku traktowaliśmy z jednej strony jako próbę porównania poziomu kształcenia na polskich uczelniach projektowych, z drugiej, chcieliśmy oczywiście choć trochę pomóc ich adeptom. Od 2005 roku wraz ze Śląskim Zamkiem Sztuki i Przedsiębiorczości w Cieszynie organizujemy cykl wystaw, na których prezentowany jest szerszy wybór prac. Obserwując rozwój przeglądu, zainteresowanie kolejnymi wystawami i dorobek autorów wyróżnionych w poprzednich edycjach, możemy jednoznacznie stwierdzić, że Przegląd przynosi efekty. Świadomi tego są też sami uczestnicy, dla których jest to bardzo wartościowy sposób promocji, szansa nawiązania pierwszych kontaktów zawodowych.\r\nW tym roku, wraz z czeskim magazynem „Typo” i słowackim „Designum”, bazując na ciekawej, liczącej już kilka lat współpracy przy projekcie wydawnictw G4, postanowiliśmy nadać kolejnej edycji status „międzynarodowego przeglądu projektów dyplomowych”. Komisja kwalifikacyjna rozpatrzy nadesłane prace w dwóch kategoriach – grafiki użytkowej i wzornictwa przemysłowego. Werdykt nie będzie mieć charakteru klasyfikacji konkursowej, nie ustalimy kolejności miejsc, nie przyznamy żadnych nagród. Wyróżnieniem będzie publikacja w magazynach „2+3D”, „Typo” i „Designum” oraz prezentacja na wystawie w Zamku Sztuki i Przedsiębiorczości w Cieszynie.\r\nZaprszamy do udziału w przeglądzie!'),
	(null,'about_text','cs','Absolventská práce, ať už magisterská nebo bakalářská, je výrazným momentem v životě designéra. Na&nbsp;jedné straně končí období studia, kdy práce vznikají pod dohledem odborníků, lektorů a profesorů, na&nbsp;straně druhé to znamená začátek samostatné designérské kariéry se vší odpovědností. V roce 2002 zorganizovali redaktoři polského čtvrtletníku 2+3D první přehlídku diplomových prací obhájených na polských katedrách designu, která byla obeslána 45 pracemi (22 z designu grafického a 23 z&nbsp;průmyslového). Každým rokem se počet přihlášek zvyšoval, až letos dosáhl 149 projektů (93 z designu grafického a 56 z průmyslového), což naznačuje přijetí této myšlenky a ocenění smyslu zveřejnit vybrané projekty na stránkách časopisu. Přehlídka byla od počátku zamýšlena jako pokus o porovnání úrovně vzdělávání na polských univerzitách a zároveň jako pomoc mladým designérům. Od roku 2005 byly ve spolupráci se Slezským zámkem umění a podnikání vybrané práce představeny na sérii výstav. Při pohledu na vývoj projektu a na úspěchy autorů, jejichž projekty byly prezentovány, lze jednoznačně říci, že přehlídka plní svůj účel. To jsou si vědomi i sami účastníci, pro které přehlídka nabízí cenný způsob propagace a šanci navázat profesionální kontakty.\r\nLetos je akce poprvé organizována jako mezinárodní projekt, na kterém s polským časopisem 2+3D spolupracuje český časopis TYPO a slovenský magazín Designum. Organizátoři navázali na úspěšnou spolupráci na projektu G4, který mapuje dění v oblasti designu v zemích visegrádské čtyřky, a rozhodli se věnovat další vydání právě Mezinárodní přehlídce absolventských projektů. Výběrová komise bude hodnotit přihlášené práce ve dvou kategoriích: grafický design (2D) a průmyslový design (3D). Komise nebude určovat pořadí vybraných návrhů a vybraní autoři nezískají žádné ocenění, neboť akce nemá povahu soutěže. Vybrané práce budou publikovány v časopisech Typo, 2+3D a Designum a budou prezentovány na sérii výstav na Slezském zámku podnikání a umění a dalších místech v Polsku, České republice a na Slovensku.\r\nZveme vás k účasti na přehlídce!'),
	(null,'about_text','sk','Absolventská práca, či už magisterská alebo bakalárska, je výrazným momentom v živote dizajnéra. Na&nbsp;jednej strane končí obdobie štúdia, kedy jeho práce vznikajú pod dohľadom odborníkov, lektorov a&nbsp;profesorov, na druhej strane to znamená začiatok samostatnej dizajnérskej kariéry so všetkou zodpovednosťou. V roku 2002 zorganizovali redaktori poľského štvrťročného periodika 2+3D prvú prehliadku diplomových prác obhájených na poľských katedrách dizajnu, ktorá bola oboslaná 45 prácami (22 z grafického dizajnu a 23 z priemyselného). Každý rok sa počet prihlášok zvyšoval, až tento rok dosiahol 149 projektov (93 z grafického dizajnu a 56 z priemyselného), čo naznačuje prijatie tejto myšlienky a ocenenie zmyslu zverejniť vybrané projekty na stránkach časopisu. Prehliadka bola od začiatku zamýšľaná ako pokus o porovnanie úrovne vzdelávania na poľských univerzitách a zároveň ako pomoc mladým dizajnérom. Od roku 2005 boli v spolupráci s Sliezskym zámkom umenia a podnikania vybrané práce predstavené na sérii výstav. Pri pohľadu na vývoj projektu a úspechy autorov, ktorých projekty boli prezentované, možno jednoznačne povedať, že prehliadka plní svoj účel. To si uvedomili aj sami účastníci, pre ktorých prehliadka ponúka cenný spôsob propagácie a šancu nadviazať profesionálne kontakty.\r\nTento rok je akcia prvýkrát organizovaná ako medzinárodný projekt, na ktorom spolupracuje český časopis TYPO a slovenský magazín Designum. Organizátori nadviazali na úspešnú spoluprácu na projekte G4, ktorý mapuje dianie v oblasti dizajnu v krajinách vyšehradskej štvorky, a rozhodli sa venovať ďalšie vydanie práve Medzinárodnej prehliadke absolventských projektov. Výberová komisia bude hodnotiť prihlásené práce v dvoch kategóriách: grafický dizajn (2D) a priemyselný dizajn (3D). Komisia nebude určovať poradie vybraných návrhov a vybraní autori nezískajú žiadne ocenenie, pretože akcia nemá povahu súťaže. Vybrané práce budú publikované v časopisoch Typo, 2+3D a Designum a budú prezentované na sérii výstav na Sliezskom zámku podnikania a umenia a ďalších miestach v Poľsku, Českej republike a na Slovensku.\r\nPozývame vás zúčastniť sa na prehliadke!'),
	(null,'about_header','pl','Międzynarodowy Przegląd Projektów Dyplomowych'),
	(null,'about_header','cs','Mezinárodní přehlídka <br>absolventských prací'),
	(null,'about_header','sk','Medzinárodná prehliadka <br>absolventských prác'),
	(null,'faq_header','pl','Najczęściej zadawane pytania'),
	(null,'faq_header','cs','Často kladené otázky'),
	(null,'faq_header','sk','Často kladené otázky'),
	(null,'regulation_header','pl','Regulamin przeglądu'),
	(null,'regulation_header','cs','Pravidla'),
	(null,'regulation_header','sk','Pravidlá'),
	(null,'access_denied','pl','Dostęp do tej części wymaga zalogowania'),
	(null,'access_denied','cs','You need to log in to access this part of the site'),
	(null,'access_denied','sk','You need to log in to access this part of the site'),
	(null,'login_success','pl','Logowanie poprawne'),
	(null,'login_success','cs','Login successful'),
	(null,'login_success','sk','Login successful'),
	(null,'login_header','pl','Logowanie'),
	(null,'login_header','cs','Log in'),
	(null,'login_header','sk','Log in'),
	(null,'user','pl','Użytkownik'),
	(null,'user','cs','User'),
	(null,'user','sk','User'),
	(null,'password','pl','Hasło'),
	(null,'password','cs','Password'),
	(null,'password','sk','Password'),
	(null,'password_repeat','pl','Powtórz hasło'),
	(null,'password_repeat','cs','Password'),
	(null,'password_repeat','sk','Password'),
	(null,'login_submit','pl','Zaloguj'),
	(null,'login_submi','cs','Log in'),
	(null,'login_submit','sk','Log in'),
	(null,'previous_page','pl','Poprzednia strona'),
	(null,'previous_page','cs','Přechozí stránka'),
	(null,'previous_page','sk','Predchádzajúca stránka'),
	(null,'faq_link','pl','FAQ'),
	(null,'faq_link','sk','FAQ'),
	(null,'faq_link','cs','FAQ'),
	(null,'logout','pl','Wylogowano'),
	(null,'logout','sk','Logout'),
	(null,'logout','cs','Logout'),
	(null,'mail_title','pl','Tytuł wiadomości'),
	(null,'mail_title','sk','Názov správy'),
	(null,'mail_title','cs','Název zprávy'),
	(null,'contact_header','pl','Kontakt'),
	(null,'contact_header','sk','Kontakt'),
	(null,'contact_header','cs','Kontakt'),
	(null,'contact_submit','pl','Wyślij wiadomość'),
	(null,'contact_submit','sk','Odoslať správu'),
	(null,'contact_submit','cs','Odeslat zprávu'),
	(null,'contact_name','pl','Imię i nazwisko'),
	(null,'contact_email','pl','Twój adres e-mail'),
	(null,'mail_text','pl','Treść wiadomości'),
	(null,'contact_email','cs','E-mailová adresa'),
	(null,'contact_name','cs','Jméno a příjmení'),
	(null,'mail_text','cs','Obsah zprávy'),
	(null,'mail_text_count','cs','(max. 5000 znaků)'),
	(null,'mail_text_count','pl','(max 5000 znaków)'),
	(null,'mail_text_count','sk','(max 5000 znakov)'),
	(null,'mail_text','sk','Obsah správy'),
	(null,'contact_email','sk','E-mailová adresa'),
	(null,'contact_name','sk','Meno a priezvisko'),
	(null,'counter','pl','Ilość znaków'),
	(null,'counter','sk','Počet znakov'),
	(null,'counter','cs','Počet znaků'),
	(null,'isEmpty','pl','Pole jest wymagane'),
	(null,'isEmpty','sk','Povinný údaj'),
	(null,'stringLengthTooShort','cs','Povinný údaj'),
	(null,'stringLengthTooShort','pl','Pole zawiera zbyt mało znaków'),
	(null,'\'%value%\' does not match against pattern \'%pattern%\'','pl','To nie jest poprawna wartość dla tego pola'),
	(null,'emailAddressInvalidHostname', 'pl','To nie jest poprawny format e-mail'),
	(null,'emailAddressInvalidHostname', 'cs','To nie jest poprawny format e-mail'),
	(null,'emailAddressInvalidHostname', 'sk','To nie jest poprawny format e-mail'),
	(null,'hostnameUnknownTld', 'pl','Nieznany adres hosta'),
	(null,'hostnameUnknownTld', 'cs','To nie jest poprawny format e-mail'),
	(null,'hostnameUnknownTld', 'sk','To nie jest poprawny format e-mail'),
	(null,'hostnameLocalNameNotAllowed', 'pl','Niedozwolona domena najwyższego poziomu'),
	(null,'hostnameLocalNameNotAllowed', 'cs','To nie jest poprawny format e-mail'),
	(null,'hostnameLocalNameNotAllowed', 'sk','To nie jest poprawny format e-mail'),
	(null,'stringLengthTooLong','pl','Pole zawiera zbyt wiele znaków'),
	(null,'Could not open socket','pl','Nie można nawiązać połączenia'),
	(null,'Could not open socket','sk','Could not open socket'),
	(null,'Could not open socket','cs','Could not open socket'),
	(null,'mail_success','pl','Dziękujemy za zgłoszenie.'),
	(null,'Connection timeout','pl','Przekroczono czas nawiązania połączenia'),
	(null,'Connection timeout','cs','Connection timeout'),
	(null,'Connection timeout','sk','Connection timeout'),
	(null,'mail_error_header','pl','Wystąpił błąd podczas próby wysłania e-maila.'),
	(null,'mail_error_header','sk','An error occurred during sending e-mail'),
	(null,'mail_error_header','sk','An error occurred during sending e-mail'),
	(null,'notSame','pl','Błędne potwierdzenie'),
	(null,'notSame','sk','The field value dosen\'t match'),
	(null,'notSame','cs','The field value dosen\'t match'),
	(null,'mail_success_header','pl','Wiadomość została wysłana'),
	(null,'mail_success_header','cs','The message has been sent'),
	(null,'mail_success_header','sk','The message has been sent'),
	(null,'country','pl','Kraj'),
	(null,'country','sk','Country'),
	(null,'country','cs','Country'),
	(null,'Poland','pl','Polska'),
	(null,'Poland','cs','Poland'),
	(null,'Poland','sk','Poland'),
	(null,'application_submit','pl','Wyślij zgłoszenie'),
	(null,'application_submit','sk','Apply'),
	(null,'application_submit','cs','Apply'),
	(null,'Slovakia','pl','Słowacja'),
	(null,'Slovakia','sk','Slovakia'),
	(null,'Slovakia','cs','Slovakia'),
	(null,'Czech Republic','pl','Republika Czeska'),
	(null,'Czech Republic','cs','Czech Republic'),
	(null,'Czech Republic','sk','Czech Republic'),
	(null,'paragraph_no','pl','Numer porządkowy'),
	(null,'paragraph_no','sk','Paragraph number'),
	(null,'paragraph_no','cs','Paragraph number'),
	(null,'paragraph_text','pl','Treść paragrafu'),
	(null,'paragraph_text','sk','Paragraph text'),
	(null,'paragraph_text','cs','Paragraph text'),
	(null,'regulation_submit','pl','Zapisz'),
	(null,'regulation_submit','sk','Submit'),
	(null,'regulation_submit','cs','Submit'),
	(null,'leave','pl','Zrezygnuj'),
	(null,'leave','sk','Leave'),
	(null,'leave','cs','Leave'),
	(null,'edit','pl','Edytuj'),
	(null,'edit','cs','Edit'),
	(null,'edit','sk','Edit'),
	(null,'delete','pl','Usuń'),
	(null,'delete','cs','Delete'),
	(null,'delete','sk','Delete'),
	(null,'paragraph_remove','pl','Usuń paragraf'),
	(null,'paragraph_remove','cs','Remove this paragraph'),
	(null,'paragraph_remove','sk','Remove this paragrap'),
	(null,'application_header','pl','Zgłoś pracę'),
	(null,'application_header','cs','Remove this paragraph'),
	(null,'application_header','sk','Remove this paragrap'),
	(null,'add_new_school','pl','-- Wybierz swoją uczelnię --'),
	(null,'add_new_school','cs','Add new school'),
	(null,'add_new_school','sk','Add new school'),
	(null,'user_name','pl','Imię'),
	(null,'user_surname','pl','Nazwisko'),
	(null,'nick','pl','Login'),
	(null,'password','pl','Hasło'),
	(null,'confirm_password','pl','Powtórz'),
	(null,'address','pl','Adres'),
	(null,'phone','pl','Telefon'),
	(null,'phone_description','pl','Telefon podaj w formacie +48 123456789'),
	(null,'email','pl','E-mail'),
	(null,'show_email','pl','Proszę o podanie mojego adresu e-mailowego przy prezentowanej pracy'),
	(null,'school','pl','Uczelnia'),
	(null,'department','pl','Wydział'),
	(null,'degree','pl','Dyplom'),
	(null,'empty_degree','pl','-- Wybierz rodzaj dyplomu --'),
	(null,'B.A.','pl','Licencjat'),
	(null,'M.A.','pl','Magisterium'),
	(null,'work_subject','pl','Tytuł pracy'),
	(null,'work_type','pl','Rodzaj projektu'),
	(null,'work_desc','pl','Opis pracy'),
	(null,'work_desc_count','pl','(maksymalnie 2000 znaków)'),
	(null,'supervisor','pl','Promotor'),
	(null,'supervisor_degree','pl','Tytuł naukowy promotora'),
	(null,'graduation_time','pl','Data obrony pracy'),
	(null,'empty_type','pl','-- Wybierz typ pracy --'),  
	(null,'isEmptyCombo','pl','Jedno z pól musi zostać podane'),
	(null,'missingField','pl','Nie podano pola do sprawdzenia'),
	(null,'notMatchField','pl','Powtórzenie jest błędne'),
	(null,'dateToLate','pl','Podana data jest późniejsza niż maksymalny dopuszczalny termin'),
	(null,'dateToEarly','pl','Podana data jest wcześniejsza niż pierwszy dopuszczalny termin')
	;
	
	