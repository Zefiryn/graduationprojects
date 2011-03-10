DROP TABLE localizations;
CREATE TABLE localizations (
	item_id int NOT NULL AUTO_INCREMENT,
	name varchar(60) not null,
	lang_code char(4) not null,
	text text not null,
	PRIMARY KEY (item_id)
) ENGINE=INNODB DEFAULT CHARSET=utf8;

INSERT INTO localizations VALUES
	(null,'footer_visegrad','pl','Projekt wydany dzięki pomocy finansowej Międzynarodowej Fundacji Wyszehradzkiej www.visegrad.eu'),
	(null,'footer_visegrad','cs','Projekt finančně podpořil Mezinárodní visegradský fond www.visegradfund.org'),
	(null,'footer_visegrad','sk','Projekt bol finančne podporený Medzinárodným vyšehradským fondom www.visegradfund.org'),
	(null,'Partners','pl','Partnerzy'),
	(null,'meta_title','pl','Międzynarodowy przegląd projektów dyplomowych'),
	(null,'main_page_header','pl','Międzynarodowy <br>przegląd <br>projektów <br>dyplomowych'),
	(null,'main_page_text','pl','Praca dyplomowa – tak magisterska, jak licencjacka – jest szczególnym momentem w&nbsp;życiu projektanta. Z jednej strony to zamknięcie, podsumowanie czasu studiów, cieplarnianej jednak sytuacji pracy pod bacznym okiem fachowców, wykładowców i profesorów, z&nbsp;drugiej strony, dla wielu to pierwszy projekt prezentowany w portfolio i początek pracy projektowej prowadzonej już na własny rachunek i odpowiedzialność. W 2002 roku redakcja „2+3D” ogłosiła pierwszy przegląd projektów dyplomowych, obronionych na polskich uczelniach projektowych. W tym roku, wraz z&nbsp;czeskim magazynem „Typo” i&nbsp;słowackim „Designum”, bazując na ciekawej, liczącej już kilka lat współpracy przy projekcie wydawnictw G4, postanowiliśmy nadać kolejnej edycji status przeglądu międzynarodowego”. <br><br>Zaprszamy do udziału w przeglądzie! '),
	(null,'main_page_more_link','pl','więcej'),
	(null,'regulation_link','pl','Regulamin'),
	(null,'form_link','pl','Zgłoś pracę'),
	(null,'result_link','pl','Wyniki'),
	(null,'contact_link','pl','Kontakt'),

	(null,'about_text','pl','Praca dyplomowa – tak magisterska, jak licencjacka – jest szczególnym momentem w życiu projektanta. Z jednej strony to zamknięcie, podsumowanie czasu studiów, cieplarnianej jednak sytuacji pracy pod bacznym okiem fachowców, wykładowców i profesorów, z drugiej strony, dla wielu to pierwszy projekt prezentowany w portfolio i początek pracy projektowej prowadzonej już na własny rachunek i odpowiedzialność. W 2002 roku redakcja kwartalnika „2+3D” ogłosiła pierwszy przegląd projektów dyplomowych, obronionych na polskich uczelniach projektowych, na który nadesłano 45 prac (22 z zakresu grafiki użytkowej i 23 z wzornictwa przemysłowego). Rokrocznie liczba ta zwiększała się i do ostatniej edycji zgłoszono już 149 projektów (93 z zakresu grafiki użytkowej grafiki i 56 z wzornictwa przemysłowego), co może świadczyć o szerokiej akceptacji tego pomysłu i docenieniu wartości publikacji wybranych projektów na łamach kwartalnika. Przegląd od samego początku traktowaliśmy z jednej strony jako próbę porównania poziomu kształcenia na polskich uczelniach projektowych, z drugiej, chcieliśmy oczywiście choć trochę pomóc ich adeptom. Od 2005 roku wraz ze Śląskim Zamkiem Sztuki i Przedsiębiorczości w Cieszynie organizujemy cykl wystaw, na których prezentowany jest szerszy wybór prac. Obserwując rozwój przeglądu, zainteresowanie kolejnymi wystawami i dorobek autorów wyróżnionych w poprzednich edycjach, możemy jednoznacznie stwierdzić, że Przegląd przynosi efekty. Świadomi tego są też sami uczestnicy, dla których jest to bardzo wartościowy sposób promocji, szansa nawiązania pierwszych kontaktów zawodowych.
W tym roku, wraz z czeskim magazynem „Typo” i słowackim „Designum”, bazując na ciekawej, liczącej już kilka lat współpracy przy projekcie wydawnictw G4, postanowiliśmy nadać kolejnej edycji status „międzynarodowego przeglądu projektów dyplomowych”. Komisja kwalifikacyjna rozpatrzy nadesłane prace w dwóch kategoriach – grafiki użytkowej i wzornictwa przemysłowego. Werdykt nie będzie mieć charakteru klasyfikacji konkursowej, nie ustalimy kolejności miejsc, nie przyznamy żadnych nagród. Wyróżnieniem będzie publikacja w magazynach „2+3D”, „Typo” i „Designum” oraz prezentacja na wystawie w Zamku Sztuki i Przedsiębiorczości w Cieszynie.
Zaprszamy do udziału w przeglądzie!'),
	(null,'about_header','pl','Międzynarodowy Przegląd Projektów Dyplomowych'),
	(null,'faq_header','pl','Najczęściej zadawane pytania'),
	(null,'regulation_header','pl','Regulamin przeglądu'),
	(null,'access_denied','pl','Dostęp do tej części wymaga zalogowania'),
	(null,'login_success','pl','Logowanie poprawne'),
	(null,'login_header','pl','Logowanie'),
	(null,'user','pl','Użytkownik'),
	(null,'password','pl','Hasło'),
	(null,'password_repeat','pl','Powtórz hasło'),
	(null,'login_submit','pl','Zaloguj'),
	(null,'previous_page','pl','Poprzednia strona'),
	(null,'faq_link','pl','FAQ'),
	(null,'logout','pl','Wylogowano'),
	(null,'mail_title','pl','Tytuł wiadomości'),
	(null,'contact_header','pl','Kontakt'),
	(null,'contact_submit','pl','Wyślij wiadomość'),
	(null,'contact_name','pl','Imię i nazwisko'),
	(null,'contact_email','pl','Twój adres e-mail'),
	(null,'mail_text','pl','Treść wiadomości'),
	(null,'mail_text_count','pl','(max 5000 znaków)'),
	(null,'counter','pl','Ilość znaków'),
	(null,'isEmpty','pl','Pole jest wymagane'),
	(null,'stringLengthTooShort','pl','Pole zawiera zbyt mało znaków'),
	(null,'\'%value%\' does not match against pattern \'%pattern%\'','pl','To nie jest poprawna wartość dla tego pola'),
	(null,'emailAddressInvalidHostname', 'pl','To nie jest poprawny format e-mail'),
	(null,'hostnameUnknownTld', 'pl','Nieznany adres hosta'),
	(null,'hostnameLocalNameNotAllowed', 'pl','Niedozwolona domena najwyższego poziomu'),
	(null,'stringLengthTooLong','pl','Pole zawiera zbyt wiele znaków'),
	(null,'Could not open socket','pl','Nie można nawiązać połączenia'),
	(null,'mail_success','pl','Dziękujemy za zgłoszenie.'),
	(null,'Connection timeout','pl','Przekroczono czas nawiązania połączenia'),
	(null,'mail_error_header','pl','Wystąpił błąd podczas próby wysłania e-maila.'),
	(null,'notSame','pl','Błędne potwierdzenie'),
	(null,'mail_success_header','pl','Wiadomość została wysłana'),
	(null,'country','pl','Kraj'),
	(null,'Poland','pl','Polska'),
	(null,'Slovakia','pl','Słowacja'),
	(null,'Czech Republic','pl','Republika Czeska'),
	(null,'application_submit','pl','Wyślij zgłoszenie'),
	(null,'paragraph_no','pl','Numer porządkowy'),
	(null,'paragraph_text','pl','Treść paragrafu'),
	(null,'regulation_submit','pl','Zapisz'),
	(null,'leave','pl','Zrezygnuj'),
	(null,'edit','pl','Edytuj'),
	(null,'delete','pl','Usuń'),
	(null,'paragraph_remove','pl','Usuń paragraf'),
	(null,'application_header','pl','Zgłoś pracę'),
	(null,'applications_header','pl','Zgłoszenia'),
	(null,'application_edit_header','pl','Edytuj zgłoszenie'),
	(null,'add_new_school','pl','-- Wybierz swoją uczelnię --'),
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
	(null,'miniature','pl','Miniatura'),
	(null,'new_miniature','pl','Zmień obrazek miniatury'),
	(null,'miniature_description','pl','Jeden obrazek prezentujący pracę w rozmiarze 800x800 pikseli'),
	(null,'file','pl','Plik'),
	(null,'new_file','pl','Zmień plik'),
	(null,'file_uploaded','pl','Przesłany plik'),
	(null,'file_description','pl','Dodaj pliki JPG lub PNG'),
	(null,'file_annotation','pl','Opis ilustracji'),
	(null,'application_added','pl','Zgłoszenie zostało przyjęte'),
	(null,'personal_data_agreement','pl','Wyrażam zgodę na przetwarzanie moich danych osobowych zawartych w formularzu dla potrzeb przeprowadzenia konkursu, zgodnie z ustawą z dnia 29.08.1997 r.  o ochronie danych osobowych. (Dz. U. z 2002 r. nr 101, poz. 926 ze zm.)'),
	(null,'empty_type','pl','-- Wybierz typ pracy --'),  
	(null,'isEmptyCombo','pl','Jedno z pól musi zostać podane'),
	(null,'missingField','pl','Nie podano pola do sprawdzenia'),
	(null,'notMatchField','pl','Powtórzenie jest błędne'),
	(null,'dateToLate','pl','Podana data jest późniejsza niż maksymalny dopuszczalny termin'),
	(null,'dateToEarly','pl','Podana data jest wcześniejsza niż pierwszy dopuszczalny termin'),
	(null,'wrongDate','pl','To nie jest poprawna data'),
	(null,'userExist','pl','Podana nazwa użytkownika została już wykorzystana'),
	(null,'emailExist','pl','Podany e-mail został już wykorzystany'),
	(null,'fileUploadErrorNoFile','pl','Ten plik jest wymagany'),
	(null,'csrf_error','pl','Formularz nie został został poprawnie rozpoznay. Spróbuj przesłać ponownie.'),
	(null,'fileExtensionFalse','pl','Przesłany plik ma niedozwolone rozszerzenie.'),
	(null,'fileMimeTypeFalse','pl','Przesłano niedozwolony rodzaj pliku.'),
	(null,'fileSizeTooBig','pl','Plik przekracza dopuszczalną wartość %max%.'),
	(null,'fileImageSizeWidthTooBig','pl','Szerokość tego obrazka jest większa niż %maxwidth%px.'),
	(null,'fileImageSizeWidthTooSmall','pl','Szerokość tego obrazka jest mniejsza niż %minwidth%px.'),
	(null,'fileImageSizeHeightTooBig','pl','Wysokość tego obrazka jest większa niż %maxheight%px.'),
	(null,'fileImageSizeHeightTooSmall','pl','Wysokość tego obrazka jest mniejsza niż %minheight%px.'),
	(null,'files_info','pl','Pliki w formacie JPG lub PNG'),
	(null,'files','pl','Pliki'),
	(null,'max_files','pl','Maksymalnie plików'),
	(null,'max_file_size','pl','Maksymalny rozmiar pliku'),
	(null,'edition_choice','pl','Edycja'),
	(null,'sort_type','pl','Pokaż zgłoszenia'),
	(null,'user_name_and_surname','pl','Imię i nazwisko'),
	(null,'application_files','pl','Pliki'),
	(null,'vote','pl','Oceny'),
	(null,'login_error','pl','Nieprawidłowa nazwa użytkownika lub hasło'),
	(null,'submit','pl','Wyślij'),
	(null,'about','pl','O konkursie'),
	(null,'pl','pl','Polska'),
	(null,'cs','pl','Czechy'),
	(null,'sk','pl','Słowacja'),
	(null,'annotations','pl','Notatki'),
	(null,'application_date','pl','Data zgłoszenia'),
	(null,'users_header','pl','Użytkownicy'),
	(null,'error_occured','pl','Wystąpił błąd'),
	(null,'exception_information','pl','Informacja o wyjątku'),
	(null,'message','pl','Wiadomość'),
	(null,'Application error','pl','Błąd aplikacji'),
	(null,'stack_trace','pl','Stos wywołań'),
	(null,'request_parameters','pl','Parametry żądania'),
	(null,'admins','pl','Administratorzy'),
	(null,'jurors','pl','Jurorzy'),
	(null,'users','pl','Użytkownicy'),
	(null,'application','pl','Zgłoszenie'),
	(null,'admin','pl','Administrator'),
	(null,'juror','pl','Juror'),
	(null,'user','pl','Użytkownik'),
	(null,'new_user','pl','Dodaj nowego użytkownika'),
	(null,'edit_user','pl','Edytuj dane użytkownika'),
	(null,'role','pl','Uprawnienia'),
	
	(null,'Partners','cs','Partneři'),
	(null,'meta_title','cs','Central European Review – Graduation Projects'),
	(null,'main_page_header','cs','Mezinárodní <br>přehlídka <br>absolventských <br>prací'),
	(null,'main_page_text','cs','Absolventská práce, ať už magisterská nebo bakalářská, je výrazným momentem v životě designéra. Na jedné straně končí období studia, kdy práce vznikají pod dohledem odborníků, lektorů a profesorů, na straně druhé to znamená začátek samostatné designérské kariéry se vší odpovědností. V roce 2002 zorganizovali redaktoři polského čtvrtletníku 2+3D první přehlídku diplomových prací obhájených na polských katedrách designu. Letos je akce poprvé&nbsp;organizována jako mezinárodní projekt, na kterém s polským časopisem 2+3D spolupracuje český časopis TYPO a slovenský magazín Designum. Organizátoři navázali na úspěšnou spolupráci na projektu G4, který mapuje dění v&nbsp;oblasti designu v zemích visegrádské čtyřky. <br><br>Zveme vás k účasti na přehlídce!'),
	(null,'main_page_more_link','cs','více'),
	(null,'regulation_link','cs','Pravidla'),
	(null,'form_link','cs','Přihlásit práce'),
	(null,'result_link','cs','Výsledky'),
	(null,'contact_link','cs','Kontakt'),
	(null,'Partners','sk','Partneri'),
	(null,'meta_title','sk','Medzinárodná prehliadka absolventských prác'),
	(null,'main_page_header','sk','Medzinárodná <br>prehliadka <br>absolventských <br>prác'),
	(null,'main_page_text','sk','Absolventská práca, či už magisterská alebo bakalárska, je výrazným momentom v živote dizajnéra. Na jednej strane končí obdobie štúdia, kedy jeho práce vznikajú pod dohľadom odborníkov, lektorov a profesorov, na druhej strane to znamená začiatok samostatnej dizajnérskej kariéry so všetkou zodpovednosťou. V roku 2002 zorganizovali redaktori poľského štvrťročného periodika 2+3D prvú prehliadku diplomových prác obhájených na poľských katedrách dizajnu. Tento rok je akcia prvýkrát organizovaná ako medzinárodný projekt, na&nbsp;ktorom spolupracuje český časopis TYPO a&nbsp;slovenský magazín Designum. Organizátori nadviazali na úspešnú spoluprácu na projekte G4, ktorý mapuje dianie v oblasti dizajnu v krajinách vyšehradskej štvorky. <br><br>Pozývame vás zúčastniť sa na prehliadke!'),(null,'main_page_more_link','sk','viac'),
	(null,'regulation_link','sk','Pravidlá'),
	(null,'form_link','sk','Prihlásiť práce'),
	(null,'result_link','sk','Výsledky'),
	(null,'contact_link','sk','Kontakt')
	
	
	
	;
	
	
	