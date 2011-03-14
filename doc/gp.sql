-- MySQL dump 10.13  Distrib 5.1.55, for mandriva-linux-gnu (i586)
--
-- Host: localhost    Database: graduationprojects
-- ------------------------------------------------------
-- Server version	5.1.55

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `applications`
--

DROP TABLE IF EXISTS `applications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `applications` (
  `application_id` smallint(6) NOT NULL AUTO_INCREMENT,
  `edition_id` smallint(6) DEFAULT NULL,
  `user_id` int(6) DEFAULT NULL,
  `country` char(2) NOT NULL,
  `school_id` smallint(6) DEFAULT NULL,
  `department` varchar(60) NOT NULL,
  `degree_id` smallint(6) DEFAULT NULL,
  `work_subject` varchar(300) NOT NULL,
  `work_type_id` smallint(6) DEFAULT NULL,
  `work_desc` text NOT NULL,
  `supervisor` varchar(60) NOT NULL,
  `supervisor_degree` varchar(15) NOT NULL,
  `graduation_time` int(11) NOT NULL,
  `application_date` int(11) NOT NULL,
  `miniature` varchar(35) DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`application_id`),
  KEY `edition_id` (`edition_id`),
  KEY `user_id` (`user_id`),
  KEY `school_id` (`school_id`),
  KEY `degree_id` (`degree_id`),
  KEY `work_type_id` (`work_type_id`),
  CONSTRAINT `applications_ibfk_1` FOREIGN KEY (`edition_id`) REFERENCES `editions` (`edition_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `applications_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `applications_ibfk_3` FOREIGN KEY (`school_id`) REFERENCES `schools` (`school_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `applications_ibfk_4` FOREIGN KEY (`degree_id`) REFERENCES `degrees` (`degree_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `applications_ibfk_5` FOREIGN KEY (`work_type_id`) REFERENCES `work_types` (`work_type_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `applications`
--

LOCK TABLES `applications` WRITE;
/*!40000 ALTER TABLE `applications` DISABLE KEYS */;
INSERT INTO `applications` VALUES (2,10,3,'pl',27,'Projektowania',4,'Projekt okładki dla książki poświęconej wychowaniu w trzeźwości',1,'Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of \"de Finibus Bonorum et Malorum\" (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, \"Lorem ipsum dolor sit amet..\", comes from a line in section 1.10.32.\r\n\r\nThe standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from \"de Finibus Bonorum et Malorum\" by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.','Jan Sznuk','dr',1281391200,1299538661,'Artur_Jewula_1.png',1),(3,10,5,'pl',26,'Projektowania',3,'Projekt podpórki na książki',2,'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec ornare arcu et elit facilisis in faucibus lacus viverra. Fusce a tincidunt dui. Vestibulum et ultricies velit. Aliquam pulvinar accumsan erat, a gravida eros ultrices in. Donec cursus rutrum erat non tempor. Sed lacinia ultricies odio, et dictum eros convallis eu. Sed imperdiet purus eu erat faucibus aliquet. Morbi ut neque augue, ut malesuada nulla. Lorem ipsum dolor sit amet, consectetur adipiscing elit. In sed lacus convallis orci pharetra suscipit. Aliquam sodales gravida tellus ac euismod. Pellentesque id malesuada orci. Vivamus vehicula felis id lorem suscipit a iaculis metus commodo. Ut suscipit sodales scelerisque. Quisque cursus vehicula sapien ut fringilla. In blandit porta adipiscing. Sed egestas leo vel lectus dictum consectetur. Suspendisse ipsum libero, ornare in aliquet vitae, pulvinar a neque.\r\n\r\nIn hac habitasse platea dictumst. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam risus eros, euismod nec blandit at, euismod pulvinar nibh. Morbi at nunc lorem, at lobortis est. Ut a dictum purus. Fusce vitae felis eget ante porta dapibus. Aenean ultricies imperdiet turpis, eget pharetra purus mollis porta. Nunc et odio turpis, vel mattis elit. Sed ultrices ligula id velit mattis vitae pulvinar dolor volutpat. Pellentesque erat nisl, condimentum ac dapibus vitae, aliquam nec erat. Vivamus eget vulputate ante. Donec auctor hendrerit diam. Donec a dolor massa.\r\n\r\nPhasellus nunc magna, dictum a rhoncus ac, porttitor a nulla. Donec volutpat felis tortor, ullamcorper tristique justo. Maecenas dolor libero, venenatis ut ornare ac, ullamcorper dictum elit. Quisque mattis, est in malesuada facilisis, urna tortor ornare turpis, sit amet ullamcorper est ante sit amet turpis. Maecenas mollis gravida tortor et malesuada.','Anna Woźniak','dr hab.',1284415200,1299875438,'Jan_Kowal_1.png',1);
/*!40000 ALTER TABLE `applications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `degrees`
--

DROP TABLE IF EXISTS `degrees`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `degrees` (
  `degree_id` smallint(6) NOT NULL AUTO_INCREMENT,
  `degree_name` char(13) NOT NULL,
  PRIMARY KEY (`degree_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `degrees`
--

LOCK TABLES `degrees` WRITE;
/*!40000 ALTER TABLE `degrees` DISABLE KEYS */;
INSERT INTO `degrees` VALUES (3,'B.A.'),(4,'M.A.');
/*!40000 ALTER TABLE `degrees` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `editions`
--

DROP TABLE IF EXISTS `editions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `editions` (
  `edition_id` smallint(6) NOT NULL AUTO_INCREMENT,
  `edition_name` char(10) NOT NULL,
  PRIMARY KEY (`edition_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `editions`
--

LOCK TABLES `editions` WRITE;
/*!40000 ALTER TABLE `editions` DISABLE KEYS */;
INSERT INTO `editions` VALUES (1,'2001/2002'),(2,'2002/2003'),(3,'2003/2004'),(4,'2004/2005'),(5,'2005/2006'),(6,'2006/2007'),(7,'2007/2008'),(9,'2008/2009'),(10,'2009/2010');
/*!40000 ALTER TABLE `editions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `faq`
--

DROP TABLE IF EXISTS `faq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `faq` (
  `faq_id` int(11) NOT NULL AUTO_INCREMENT,
  `faq_lang` char(4) NOT NULL,
  `faq_question` varchar(300) NOT NULL,
  `faq_answer` text NOT NULL,
  PRIMARY KEY (`faq_id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `faq`
--

LOCK TABLES `faq` WRITE;
/*!40000 ALTER TABLE `faq` DISABLE KEYS */;
INSERT INTO `faq` VALUES (1,'pl','Czy prace można przesyłać pocztą tradycyjną?','Nie. Prace należy przesyłać jedynie za pośrednictwem formularza. Po ogłoszeniu werdyktu przeglądu skontaktujemy się z każdym autorem w celu uzgodnienia sposobu dostarczenia plików bądź projektów zarówno do publikacji, jak i realizacji wystawy.'),(2,'pl','Jak opisać pracę?','Najlepiej opisać pracę zwięźle unikając „poetyckich”, subiektywnych rozważań. W opisie powinny się znaleźć założenia projektowe, opis grupy docelowej, szczegóły działania (np. w przypadku skomplikowanych urządzeń) oraz technologia wytwarzania i informacje o zastosowanych w pracy materiałach. Przydatne w ocenie będzie także zwrócenie uwagi na różnice projektu w stosunku do istniejących rozwiązań, lub na nowatorskie elementy zastosowane po raz pierwszy.'),(3,'pl','Jak pokazać pracę?','W ilustracjach. plikach lub zdjęciach, które są ilustracją projektu powinny się znaleźć: ogólny, całościowy obraz pracy (jeśli to niezbędne z kilku różnych ujęć); detale lub ujęcia pokazujące konstrukcję (w przypadku prac graficznych będą to np. detale typograficzne). Ujęcia powinny być zrozumiałe i klarowne dla osób, które będą je oglądały po raz pierwszy. Uważamy że klarowny i ułatwiający zrozumienie sposób opisu i pokazania pracy świadczy o umiejętnościach absolwenta i także jest brany pod uwagę przy ocenie projektu.'),(4,'pl','Czy można przesłać więcej niż 10 ilustracji?','Nie, jesteśmy przekonani że 10 ilustracji wystarczy by dobrze zaprezentować każdy projekt. Uważamy, że sposób prezentacji to też część pracy projaktanta, jesteśmy przekonani, że 12 stronicowy kalendarz, lub serię opakowań przekraczającę 10 sztuk można z powodeniem pokazać na 10 ilustracjach (najprostszym rozwiązaniem jest np. prezentacja kilku opakowań na jednej ilustracji).'),(5,'pl','Czy można jako ilustracje przesłać plansze użyte do prezentacji podczas obrony dyplomu?','Tak, ale należy pamiętać że interesuje nas zwięzłe i jak najprostsze ukazanie pracy – ułatwiające zrozumienie projektu. Plansze zawierają zazwyczaj, dużo szczegółowych informacji i dodatkowych elementów graficznych, które po pomniejszeniu do rozdzielczości ekranowej (a tak będą oglądane przez oceniających) stracą swoją czytelność.'),(6,'pl','Czy można przesłać na przegląd prace, które są zwieńczeniem pomaturalnego lub podyplomowego kursu projektowania?','Tylko pod warunkiem, że kurs zakończony jest nadaniem absolwentowi tytułu naukowego magistra lub licencjata.'),(7,'cs','Je možné práci přihlásit klasickou poštou?','Ne. Práce je nutné nahrát na stránce www.graduationprojects.eu. Po oznámení výsledků hodnotící komise bude každý autor individuálně kontaktován emailem a bude dohodnut způsob předání podkladů k prezentaci v tištěné podobě i na výstavě.'),(8,'cs','Jak popsat práci?','Práce by měly být popsány stručně, bez „poetických“ a subjektivních úvah. Součástí by měl být popis koncepce, označení cílové skupiny, výčet použitých materiálů, objasnění funkčnosti (např. u složitých zařízení). Vhodné je zmínit, v čem je projekt inovativní. U modelů a prototypů je třeba také dodat měřítko a velikost, v případě grafického designu rozměry.'),(9,'cs','Jak práce prezentovat?','Vhodné jsou kresby, renderované obrázky nebo fotografie, které jasně charakterizují projekt. Obecně by mělo jít o celkový pohled na práci (případně z různých úhlů) a záběry na detaily konstrukce, resp. tiskové detaily nebo způsob užití. Prezentace by měla být srozumitelná a jasná i lidem, kteří ji uvidí poprvé.'),(10,'cs','Mohu poslat více než 10 ukázek?','Ne, jsme přesvědčeni, že deset ilustrací pro prezentaci projektu zcela dostačuje. Věříme, že schopnost prezentace tvoří nedílnou součást práce designéra.'),(11,'cs','Mohu poslat jak fotky, tak panely, na kterých byla absolventská práce prezentována?','Ano, ale nezapomeňte, že prezentace musí být stručná a výstižná s cílem usnadnit pochopení projektu. Panely často obsahují mnoho podrobných informací, které nemusí být pro hodnotící komisi na monitorech v nízkém rozlišení patrné.'),(12,'cs','Mohu přihlásit závěrečné práce pomaturitního nebo postgraduálního kursu?','Práce je možné přihlásit pouze tehdy, obdrží-li student po absolvování kursu bakalářský nebo magisterský titul.'),(13,'sk','Je možné prácu prihlásiť klasickou poštou?','Nie. Práce je potrebné nahrať na stránke www.graduationprojects.eu. Po oznámení výsledkov hodnotiacej komisie bude každý autor individuálne kontaktovaný e-mailom a bude           s ním dohodnutý spôsob odovzdania podkladov na prezentáciu v tlačovej podobe a na výstave.'),(14,'sk','Ako popísať prácu?','Práce by mali byť popísané stručne, bez „poetických“ a subjektívnych úvah. Ich súčasťou by mal byť popis koncepcie, označenie cieľovej skupiny, zoznam použitých materiálov, objasnenie funkčnosti (napríklad u zložitých zariadení). Považujeme za vhodné zmieniť sa aj o tom, v čom je projekt inovačný. V prípade modelov a prototypov je potrebné dodať aj merítko a veľkosť, v prípade grafického dizajnu rozmery.'),(15,'sk','Ako práce prezentovať?','Vhodné sú kresby, renderované obrázky alebo fotografie, ktoré jasne charakterizujú projekt. Obecne by malo ísť o celkový pohľad na prácu (prípadne z rôznych uhlov) a zábery na detaily konštrukcie, resp. tlačové detaily alebo spôsob použitia. Prezentácia by mala byť zrozumiteľná a jasná aj ľuďom, ktorí ju uvidia prvýkrát.'),(16,'sk','Môžem poslať viac ako 10 ukážok?','Nie, sme presvedčení, že desať ilustrácií pre prezentáciu projektu úplne dostačuje. Veríme, že schopnosť prezentácie tvorí neoddeliteľnú súčasť práce dizajnéra.'),(17,'sk','Môžem poslať fotografie a panely, na ktorých bola absolventská práca prezentovaná?','Áno, ale nezabudnite, že prezentácia musí byť stručná a výstižná s cieľom uľahčiť pochopenie projektu. Panely často obsahujú veľa podrobných informácií, ktoré nemusia byť pre hodnotiacu komisiu viditeľné na monitoroch v nízkom rozlíšení.'),(18,'sk','Môžem prihlásiť záverečné práce pomaturitného alebo postgraduálneho kurzu?','Práce je možné prihlásiť iba v tom prípade, ak študent obdrží po absolvovaní kurzu bakalársky alebo magisterský titul.');
/*!40000 ALTER TABLE `faq` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `files`
--

DROP TABLE IF EXISTS `files`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `files` (
  `file_id` smallint(6) NOT NULL AUTO_INCREMENT,
  `application_id` smallint(6) NOT NULL,
  `path` varchar(150) NOT NULL,
  `file_desc` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`file_id`),
  KEY `application_id` (`application_id`),
  CONSTRAINT `files_ibfk_1` FOREIGN KEY (`application_id`) REFERENCES `applications` (`application_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `files`
--

LOCK TABLES `files` WRITE;
/*!40000 ALTER TABLE `files` DISABLE KEYS */;
INSERT INTO `files` VALUES (3,2,'PL_2d_Artur_Jewula/file_1.png','Testowy plik'),(4,2,'PL_2d_Artur_Jewula/file_2.png','Testowy plik 2'),(5,3,'PL_3d_Jan_Kowal/file_1.png','Plik pierwszy'),(6,3,'PL_3d_Jan_Kowal/file_2.png','Plik drugi');
/*!40000 ALTER TABLE `files` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `localizations`
--

DROP TABLE IF EXISTS `localizations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `localizations` (
  `item_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(60) NOT NULL,
  `lang_code` char(4) NOT NULL,
  `text` text NOT NULL,
  PRIMARY KEY (`item_id`)
) ENGINE=InnoDB AUTO_INCREMENT=180 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `localizations`
--

LOCK TABLES `localizations` WRITE;
/*!40000 ALTER TABLE `localizations` DISABLE KEYS */;
INSERT INTO `localizations` VALUES (1,'footer_visegrad','pl','Projekt wydany dzięki pomocy finansowej Międzynarodowej Fundacji Wyszehradzkiej www.visegrad.eu'),(2,'footer_visegrad','cs','Projekt finančně podpořil Mezinárodní visegradský fond www.visegradfund.org'),(3,'footer_visegrad','sk','Projekt bol finančne podporený Medzinárodným vyšehradským fondom www.visegradfund.org'),(4,'Partners','pl','Partnerzy'),(5,'meta_title','pl','Międzynarodowy przegląd projektów dyplomowych'),(6,'main_page_header','pl','Międzynarodowy <br>przegląd <br>projektów <br>dyplomowych'),(7,'main_page_text','pl','Praca dyplomowa – tak magisterska, jak licencjacka – jest szczególnym momentem w&nbsp;życiu projektanta. Z jednej strony to zamknięcie, podsumowanie czasu studiów, cieplarnianej jednak sytuacji pracy pod bacznym okiem fachowców, wykładowców i profesorów, z&nbsp;drugiej strony, dla wielu to pierwszy projekt prezentowany w portfolio i początek pracy projektowej prowadzonej już na własny rachunek i odpowiedzialność. W 2002 roku redakcja „2+3D” ogłosiła pierwszy przegląd projektów dyplomowych, obronionych na polskich uczelniach projektowych. W tym roku, wraz z&nbsp;czeskim magazynem „Typo” i&nbsp;słowackim „Designum”, bazując na ciekawej, liczącej już kilka lat współpracy przy projekcie wydawnictw G4, postanowiliśmy nadać kolejnej edycji status przeglądu międzynarodowego”. <br><br>Zaprszamy do udziału w przeglądzie! '),(8,'main_page_more_link','pl','więcej'),(9,'regulation_link','pl','Regulamin'),(10,'form_link','pl','Zgłoś pracę'),(11,'result_link','pl','Wyniki'),(12,'contact_link','pl','Kontakt'),(13,'about_text','pl','Praca dyplomowa – tak magisterska, jak licencjacka – jest szczególnym momentem w życiu projektanta. Z jednej strony to zamknięcie, podsumowanie czasu studiów, cieplarnianej jednak sytuacji pracy pod bacznym okiem fachowców, wykładowców i profesorów, z drugiej strony, dla wielu to pierwszy projekt prezentowany w portfolio i początek pracy projektowej prowadzonej już na własny rachunek i odpowiedzialność. W 2002 roku redakcja kwartalnika „2+3D” ogłosiła pierwszy przegląd projektów dyplomowych, obronionych na polskich uczelniach projektowych, na który nadesłano 45 prac (22 z zakresu grafiki użytkowej i 23 z wzornictwa przemysłowego). Rokrocznie liczba ta zwiększała się i do ostatniej edycji zgłoszono już 149 projektów (93 z zakresu grafiki użytkowej grafiki i 56 z wzornictwa przemysłowego), co może świadczyć o szerokiej akceptacji tego pomysłu i docenieniu wartości publikacji wybranych projektów na łamach kwartalnika. Przegląd od samego początku traktowaliśmy z jednej strony jako próbę porównania poziomu kształcenia na polskich uczelniach projektowych, z drugiej, chcieliśmy oczywiście choć trochę pomóc ich adeptom. Od 2005 roku wraz ze Śląskim Zamkiem Sztuki i Przedsiębiorczości w Cieszynie organizujemy cykl wystaw, na których prezentowany jest szerszy wybór prac. Obserwując rozwój przeglądu, zainteresowanie kolejnymi wystawami i dorobek autorów wyróżnionych w poprzednich edycjach, możemy jednoznacznie stwierdzić, że Przegląd przynosi efekty. Świadomi tego są też sami uczestnicy, dla których jest to bardzo wartościowy sposób promocji, szansa nawiązania pierwszych kontaktów zawodowych.\nW tym roku, wraz z czeskim magazynem „Typo” i słowackim „Designum”, bazując na ciekawej, liczącej już kilka lat współpracy przy projekcie wydawnictw G4, postanowiliśmy nadać kolejnej edycji status „międzynarodowego przeglądu projektów dyplomowych”. Komisja kwalifikacyjna rozpatrzy nadesłane prace w dwóch kategoriach – grafiki użytkowej i wzornictwa przemysłowego. Werdykt nie będzie mieć charakteru klasyfikacji konkursowej, nie ustalimy kolejności miejsc, nie przyznamy żadnych nagród. Wyróżnieniem będzie publikacja w magazynach „2+3D”, „Typo” i „Designum” oraz prezentacja na wystawie w Zamku Sztuki i Przedsiębiorczości w Cieszynie.\nZaprszamy do udziału w przeglądzie!'),(14,'about_header','pl','Międzynarodowy Przegląd Projektów Dyplomowych'),(15,'faq_header','pl','Najczęściej zadawane pytania'),(16,'regulation_header','pl','Regulamin przeglądu'),(17,'access_denied','pl','Dostęp do tej części wymaga zalogowania'),(18,'login_success','pl','Logowanie poprawne'),(19,'login_header','pl','Logowanie'),(20,'user','pl','Użytkownik'),(21,'password','pl','Hasło'),(22,'password_repeat','pl','Powtórz hasło'),(23,'login_submit','pl','Zaloguj'),(24,'previous_page','pl','Poprzednia strona'),(25,'faq_link','pl','FAQ'),(26,'successfull_logout','pl','Wylogowano'),(27,'mail_title','pl','Tytuł wiadomości'),(28,'contact_header','pl','Kontakt'),(29,'contact_submit','pl','Wyślij wiadomość'),(30,'contact_name','pl','Imię i nazwisko'),(31,'contact_email','pl','Twój adres e-mail'),(32,'mail_text','pl','Treść wiadomości'),(33,'mail_text_count','pl','(max 5000 znaków)'),(34,'counter','pl','Ilość znaków'),(35,'isEmpty','pl','Pole jest wymagane'),(36,'stringLengthTooShort','pl','Pole zawiera zbyt mało znaków'),(37,'\'%value%\' does not match against pattern \'%pattern%\'','pl','To nie jest poprawna wartość dla tego pola'),(38,'emailAddressInvalidHostname','pl','To nie jest poprawny format e-mail'),(39,'hostnameUnknownTld','pl','Nieznany adres hosta'),(40,'hostnameLocalNameNotAllowed','pl','Niedozwolona domena najwyższego poziomu'),(41,'stringLengthTooLong','pl','Pole zawiera zbyt wiele znaków'),(42,'Could not open socket','pl','Nie można nawiązać połączenia'),(43,'mail_success','pl','Dziękujemy za zgłoszenie.'),(44,'Connection timeout','pl','Przekroczono czas nawiązania połączenia'),(45,'mail_error_header','pl','Wystąpił błąd podczas próby wysłania e-maila.'),(46,'notSame','pl','Błędne potwierdzenie'),(47,'mail_success_header','pl','Wiadomość została wysłana'),(48,'country','pl','Kraj'),(49,'Poland','pl','Polska'),(50,'Slovakia','pl','Słowacja'),(51,'Czech Republic','pl','Republika Czeska'),(52,'application_submit','pl','Wyślij zgłoszenie'),(53,'paragraph_no','pl','Numer porządkowy'),(54,'paragraph_text','pl','Treść paragrafu'),(55,'regulation_submit','pl','Zapisz'),(56,'leave','pl','Zrezygnuj'),(57,'edit','pl','Edytuj'),(58,'delete','pl','Usuń'),(59,'paragraph_remove','pl','Usuń paragraf'),(60,'application_header','pl','Zgłoś pracę'),(61,'applications_header','pl','Zgłoszenia'),(62,'application_edit_header','pl','Edytuj zgłoszenie'),(63,'add_new_school','pl','-- Wybierz swoją uczelnię --'),(64,'user_name','pl','Imię'),(65,'user_surname','pl','Nazwisko'),(66,'nick','pl','Login'),(67,'password','pl','Hasło'),(68,'confirm_password','pl','Powtórz'),(69,'address','pl','Adres'),(70,'phone','pl','Telefon'),(71,'phone_description','pl','Telefon podaj w formacie +48 123456789'),(72,'email','pl','E-mail'),(73,'show_email','pl','Proszę o podanie mojego adresu e-mailowego przy prezentowanej pracy'),(74,'school','pl','Uczelnia'),(75,'department','pl','Wydział'),(76,'degree','pl','Dyplom'),(77,'empty_degree','pl','-- Wybierz rodzaj dyplomu --'),(78,'B.A.','pl','Licencjat'),(79,'M.A.','pl','Magisterium'),(80,'work_subject','pl','Tytuł pracy'),(81,'work_type','pl','Rodzaj projektu'),(82,'work_desc','pl','Opis pracy'),(83,'work_desc_count','pl','(maksymalnie 2000 znaków)'),(84,'supervisor','pl','Promotor'),(85,'supervisor_degree','pl','Tytuł naukowy promotora'),(86,'graduation_time','pl','Data obrony pracy'),(87,'miniature','pl','Miniatura'),(88,'new_miniature','pl','Zmień obrazek miniatury'),(89,'miniature_description','pl','Jeden obrazek prezentujący pracę w rozmiarze 800x800 pikseli'),(90,'file','pl','Plik'),(91,'new_file','pl','Zmień plik'),(92,'file_uploaded','pl','Przesłany plik'),(93,'file_description','pl','Dodaj pliki JPG lub PNG'),(94,'file_annotation','pl','Opis ilustracji'),(95,'application_added','pl','Zgłoszenie zostało przyjęte'),(96,'personal_data_agreement','pl','Wyrażam zgodę na przetwarzanie moich danych osobowych zawartych w formularzu dla potrzeb przeprowadzenia konkursu, zgodnie z ustawą z dnia 29.08.1997 r.  o ochronie danych osobowych. (Dz. U. z 2002 r. nr 101, poz. 926 ze zm.)'),(97,'empty_type','pl','-- Wybierz typ pracy --'),(98,'isEmptyCombo','pl','Jedno z pól musi zostać podane'),(99,'missingField','pl','Nie podano pola do sprawdzenia'),(100,'notMatchField','pl','Powtórzenie jest błędne'),(101,'dateToLate','pl','Podana data jest późniejsza niż maksymalny dopuszczalny termin'),(102,'dateToEarly','pl','Podana data jest wcześniejsza niż pierwszy dopuszczalny termin'),(103,'wrongDate','pl','To nie jest poprawna data'),(104,'userExist','pl','Podana nazwa użytkownika została już wykorzystana'),(105,'emailExist','pl','Podany e-mail został już wykorzystany'),(106,'fileUploadErrorNoFile','pl','Ten plik jest wymagany'),(107,'csrf_error','pl','Formularz nie został został poprawnie rozpoznay. Spróbuj przesłać ponownie.'),(108,'fileExtensionFalse','pl','Przesłany plik ma niedozwolone rozszerzenie.'),(109,'fileMimeTypeFalse','pl','Przesłano niedozwolony rodzaj pliku.'),(110,'fileSizeTooBig','pl','Plik przekracza dopuszczalną wartość %max%.'),(111,'fileImageSizeWidthTooBig','pl','Szerokość tego obrazka jest większa niż %maxwidth%px.'),(112,'fileImageSizeWidthTooSmall','pl','Szerokość tego obrazka jest mniejsza niż %minwidth%px.'),(113,'fileImageSizeHeightTooBig','pl','Wysokość tego obrazka jest większa niż %maxheight%px.'),(114,'fileImageSizeHeightTooSmall','pl','Wysokość tego obrazka jest mniejsza niż %minheight%px.'),(115,'files_info','pl','Pliki w formacie JPG lub PNG'),(116,'files','pl','Pliki'),(117,'max_files','pl','Maksymalnie plików'),(118,'max_file_size','pl','Maksymalny rozmiar pliku'),(119,'edition_choice','pl','Edycja'),(120,'sort_type','pl','Pokaż zgłoszenia'),(121,'user_name_and_surname','pl','Imię i nazwisko'),(122,'application_files','pl','Pliki'),(123,'vote','pl','Oceny'),(124,'login_error','pl','Nieprawidłowa nazwa użytkownika lub hasło'),(125,'submit','pl','Wyślij'),(126,'about','pl','O konkursie'),(127,'pl','pl','Polska'),(128,'cs','pl','Czechy'),(129,'sk','pl','Słowacja'),(130,'annotations','pl','Notatki'),(131,'application_date','pl','Data zgłoszenia'),(132,'users_header','pl','Użytkownicy'),(133,'error_occured','pl','Wystąpił błąd'),(134,'exception_information','pl','Informacja o wyjątku'),(135,'message','pl','Wiadomość'),(136,'Application error','pl','Błąd aplikacji'),(137,'stack_trace','pl','Stos wywołań'),(138,'request_parameters','pl','Parametry żądania'),(139,'admins','pl','Administratorzy'),(140,'jurors','pl','Jurorzy'),(141,'users','pl','Użytkownicy'),(142,'application','pl','Zgłoszenie'),(143,'admin','pl','Administrator'),(144,'juror','pl','Juror'),(145,'user','pl','Użytkownik'),(146,'guest','pl','Gość'),(147,'new_user','pl','Dodaj nowego użytkownika'),(148,'edit_user','pl','Edytuj dane użytkownika'),(149,'role','pl','Uprawnienia'),(150,'admin_panel','pl','Panel administratora'),(151,'applications','pl','Zgłoszenia'),(152,'editions','pl','Edycje'),(153,'work_types','pl','Rodzaje prac'),(154,'schools','pl','Szkoły'),(155,'localizations','pl','Tłumaczenia'),(156,'settings','pl','Ustawienia'),(157,'logout','pl','Wyloguj'),(158,'login','pl','zaloguj'),(159,'logged_as','pl','Zalogowany jako'),(160,'not_logged','pl','Nie jesteś zalogowany'),(161,'notBefore','pl','Podana data jest późniejsza od %date%'),(162,'Partners','cs','Partneři'),(163,'meta_title','cs','Central European Review – Graduation Projects'),(164,'main_page_header','cs','Mezinárodní <br>přehlídka <br>absolventských <br>prací'),(165,'main_page_text','cs','Absolventská práce, ať už magisterská nebo bakalářská, je výrazným momentem v životě designéra. Na jedné straně končí období studia, kdy práce vznikají pod dohledem odborníků, lektorů a profesorů, na straně druhé to znamená začátek samostatné designérské kariéry se vší odpovědností. V roce 2002 zorganizovali redaktoři polského čtvrtletníku 2+3D první přehlídku diplomových prací obhájených na polských katedrách designu. Letos je akce poprvé&nbsp;organizována jako mezinárodní projekt, na kterém s polským časopisem 2+3D spolupracuje český časopis TYPO a slovenský magazín Designum. Organizátoři navázali na úspěšnou spolupráci na projektu G4, který mapuje dění v&nbsp;oblasti designu v zemích visegrádské čtyřky. <br><br>Zveme vás k účasti na přehlídce!'),(166,'main_page_more_link','cs','více'),(167,'regulation_link','cs','Pravidla'),(168,'form_link','cs','Přihlásit práce'),(169,'result_link','cs','Výsledky'),(170,'contact_link','cs','Kontakt'),(171,'Partners','sk','Partneri'),(172,'meta_title','sk','Medzinárodná prehliadka absolventských prác'),(173,'main_page_header','sk','Medzinárodná <br>prehliadka <br>absolventských <br>prác'),(174,'main_page_text','sk','Absolventská práca, či už magisterská alebo bakalárska, je výrazným momentom v živote dizajnéra. Na jednej strane končí obdobie štúdia, kedy jeho práce vznikajú pod dohľadom odborníkov, lektorov a profesorov, na druhej strane to znamená začiatok samostatnej dizajnérskej kariéry so všetkou zodpovednosťou. V roku 2002 zorganizovali redaktori poľského štvrťročného periodika 2+3D prvú prehliadku diplomových prác obhájených na poľských katedrách dizajnu. Tento rok je akcia prvýkrát organizovaná ako medzinárodný projekt, na&nbsp;ktorom spolupracuje český časopis TYPO a&nbsp;slovenský magazín Designum. Organizátori nadviazali na úspešnú spoluprácu na projekte G4, ktorý mapuje dianie v oblasti dizajnu v krajinách vyšehradskej štvorky. <br><br>Pozývame vás zúčastniť sa na prehliadke!'),(175,'main_page_more_link','sk','viac'),(176,'regulation_link','sk','Pravidlá'),(177,'form_link','sk','Prihlásiť práce'),(178,'result_link','sk','Výsledky'),(179,'contact_link','sk','Kontakt');
/*!40000 ALTER TABLE `localizations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `regulations`
--

DROP TABLE IF EXISTS `regulations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `regulations` (
  `paragraph_id` int(11) NOT NULL AUTO_INCREMENT,
  `edition_id` smallint(6) NOT NULL,
  `regulation_lang` char(4) NOT NULL,
  `paragraph_no` smallint(6) NOT NULL,
  `paragraph_text` text NOT NULL,
  PRIMARY KEY (`paragraph_id`),
  KEY `edition_id` (`edition_id`),
  CONSTRAINT `regulations_ibfk_1` FOREIGN KEY (`edition_id`) REFERENCES `editions` (`edition_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `regulations`
--

LOCK TABLES `regulations` WRITE;
/*!40000 ALTER TABLE `regulations` DISABLE KEYS */;
INSERT INTO `regulations` VALUES (1,10,'pl',1,'Central European Review – Graduation Projects jest międzynarodowym, środkowoeuropejskim przeglądem projektów dyplomowych (magisterskich i licencjackich) z zakresu szeroko rozumianej grafiki użytkowej (2D) i wzornictwa przemysłowego (3D) – w tym również z projektowania tkaniny, ubioru, szkła lub ceramiki, z wyjątkiem dzieł unikatowych.'),(2,10,'pl',3,'Przegląd nie ma charakteru konkursu, jest wyborem redakcji czasopism „2+3D” (PL), „Designum” (SK) i „Typo” (CZ) oraz Śląskiego Zamku Sztuki i Przedsiębiorczości w Cieszynie, zwanych dalej organizatorami, realizowanym dzięki pomocy finansowej Międzynarodowej Fundacji Wyszehradzkiej.'),(3,10,'pl',2,'W przeglądzie mogą brać udział prace dyplomowe (magisterskie i licencjackie) obronione od 1 grudnia 2009 do 30 listopada 2010.'),(4,10,'pl',4,'Do przeglądu zgłaszane mogą być tylko prace dyplomowe obronione przez obywateli Polski, Republiki Czeskiej i Słowacji na uczelniach krajowych lub za granicą.'),(5,10,'pl',5,'Do przeglądu prace zgłaszają autorzy, w przypadku prac grupowych – zespoły projektowe.'),(6,10,'pl',6,'Projekty należy zgłaszać do 30 listopada 2010.'),(7,10,'pl',7,'Podstawą zgłoszenia pracy do przeglądu jest wypełnienie formularza dostępnego na stronie www.graduationprojects.eu, wgranie maksymalnie 10 ilustracji w plikach pdf lub jpg, zapisanych w rozdzielczości ekranowej (ok. 1200 × 1600 pikseli) oraz opisu pracy (max. 2000 znaków).'),(8,10,'pl',8,'Autorzy prac wyrażają zgodę na bezpłatną publikację i prezentację projektów zgłaszanych do przeglądu oraz na przetwarzanie i wykorzystywanie ich danych osobowych w celu publikacji, promocji i realizacji wystawy.'),(9,10,'pl',9,'Komisja kwalifikacyjna przeglądu składać się będzie z przedstawicieli redakcji „Typo”, „2+3D” i „Designum” (po 2 głosy) oraz Śląskiego Zamku Sztuki i Przedsiębiorczości w Cieszynie (1 głos).'),(10,10,'pl',10,'Wytypowane prace zostaną przedstawione na łamach wydawnictw organizatorów, na stronie internetowej przeglądu (www.graduationprojects.eu) oraz na wystawie i w katalogu.'),(11,10,'pl',11,'Liczbę prac typowanych do publikacji i na wystawę określa komisja kwalifikacyjna. Ostateczne decyzje o ilości i doborze prac prezentowanych w czasopismach podejmują poszczególne redakcje.'),(12,10,'pl',12,'O werdykcie komisji kwalifikacyjnej organizatorzy poinformują uczestników drogą e‑mailową oraz na stronie internetowej przeglądu nie później niż 5 stycznia 2011.'),(13,10,'pl',13,'Organizatorzy uzgodnią drogą e‑mailową z autorami wybranych prac termin i sposób ich przygotowania do publikacji i wystawy.'),(14,10,'pl',14,'We wszelkich nie ujętych w regulaminie sprawach decydujący głos należy do organizatorów przeglądu.'),(15,10,'pl',15,'W sprawach związanych z przeglądem należy kontaktować się e‑mailowo w języku angielskim z komisarzem przeglądu Tomaszem Budzyniem: review@graduationprojects.eu lub za pomocą formularza kontaktowego.'),(16,10,'cs',1,'Central European Review – Graduation Projects je mezinárodní středoevropská přehlídka absolventských prací (magisterských a bakalářských) zahrnující jak široké spektrum grafického designu (2D), tak průmyslový design (3D), včetně návrhů textilu, oděvů, skla či keramiky, s výjimkou jedinečných uměleckých děl.'),(17,10,'cs',2,'Přehlídka nemá charakter soutěže, jde o výběr redaktorů časopisu 2+3D (Polsko), Designum (Slovensko), Typo (ČR) a Slezského zámku umění a podnikání v Cieszyně (dále jen organizátoři) za finanční podpory Mezinárodního visegrádského fondu.'),(18,10,'cs',3,'Do přehlídky mohou být přihlášeny jen absolventské práce (magisterské a bakalářské), které byly obhájeny od 1. prosince 2009 do 30. listopadu 2010.'),(19,10,'cs',4,'Do přehlídky mohou být přihlášeny pouze práce obhájené občany Polska, České repubilky a Slovenska na domácích i zahraničních univerzitách.'),(20,10,'cs',5,'Práce přihlašují autoři, v případě kolektivních děl projektové týmy.'),(21,10,'cs',6,'Projekty musí být přihlášeny do 30. listopadu 2010.'),(22,10,'cs',7,'Pro přihlášení je nutné vyplnit formulář dostupný na  www.graduationprojects.eu, nahrát maximálně 10 snímků ve formátu PDF nebo JPEG (v rozlišení cca 1600 × 1200 pixelů) a vyplnit popis práce v rozsahu max. 2000 znaků.'),(23,10,'cs',8,'Autoři souhlasí se zveřejněním a prezentacemi přihlášených projektů a se zpracováním osobních údajů za účelem propagace a realizace výstavy a katalogu.'),(24,10,'cs',9,'Hodnotící komise bude složena po dvou zástupcích z redakcí každého z časopisů 2+3D (Polsko), Designum (Slovensko) a Typo (ČR) a jedním zástupcem Slezského zámku umění a podnikání v Cieszyně.'),(25,10,'cs',10,'Vybrané práce budou prezentovány na webových stránkách organizátorů, webových stránkách přehlídky (www.graduationprojects.eu), na výstavě a v katalogu.'),(26,10,'cs',11,'Počet děl vybraných pro publikování i výstavu určí hodnotící komise. Konečné rozhodnutí týkající se množství a výběru zveřejněných prací mají redakce jednotlivých časopisů.'),(27,10,'cs',12,'Zápis z jednání hodnotící komise obdrží účastníci prostřednictvím e-mailu nejpozději 5. ledna 2011, kdy bude výsledek zveřejněn také na internetových stránkách přehlídky.'),(28,10,'cs',13,'Organizátoři se s autory vybraných prací dohodnou na způsobu jejich prezentace v tištěné podobě i na výstavě prostřednictvím e-mailu.'),(29,10,'cs',14,'Ve všech ostatních případech, které nejsou uvedeny v těchto pravidlech, mají poslední slovo organizátoři přehlídky.'),(30,10,'cs',15,'Veškeré dotazy týkající se přehlídky vyřizuje v anglickém jazyce komisař Tomasz Budzyń na e-mailu review@graduationprojects.eu nebo prostřednictvím kontaktního formuláře.'),(31,10,'sk',1,'Central European Review – Graduation Projects je medzinárodná stredoeurópska prehliadka absolventských prác (magisterských a bakalárskych) zahŕňajúca široké spektrum grafického dizajnu (2D), ako aj priemyselný dizajn (3D), vrátane návrhov textilu, odevov, skla či keramiky, s výnimkou jedinečných umeleckých diel.'),(32,10,'sk',2,'Prehliadka nemá charakter súťaže, jedná sa o výber redaktorov časopisu 2+3D (Poľsko), Designum (Slovensko), Typo (ČR) a Sliezskeho zámku umenia a podnikania v Cieszyne (ďalej len organizátori), za finančnej podpory Medzinárodného vyšehradského fondu.'),(33,10,'sk',3,'Do prehliadky môžu byť prihlásené iba absolventské práce (magisterské a bakalárske), ktoré boli obhájené od 1. decembra 2009 do 30. novembra 2010.'),(34,10,'sk',4,'Do prehliadky môžu byť prihlásené výlučne práce obhájené občanmi Poľska, Českej republiky a Slovenska na domácich i zahraničných univerzitách.'),(35,10,'sk',5,'Práce prihlasujú autori, v prípade kolektívnych diel projektové tímy.'),(36,10,'sk',6,'Projekty musia byť prihlásené do 30. novembra 2010.'),(37,10,'sk',7,'Pre prihlásenie je potrebné vyplniť formulár dostupný na www.graduationprojects.eu, nahrať maximálne 10 snímok vo formáte PDF alebo JPEG (v rozlíšení cca 1600 × 1200 pixlov) a vyplniť popis práce v rozsahu max. 2000 znakov.'),(38,10,'sk',8,'Autori súhlasia so zverejnením a prezentáciami prihlásených projektov a so spracovaním osobných údajov za účelom propagácie a realizácie výstavy a katalógu.'),(39,10,'sk',9,'Hodnotiaca komisia bude zložená po dvoch zástupcoch z redakcií každého z časopisov 2+3D (Poľsko), Designum (Slovensko) a Typo (ČR) a jedným zástupcom Sliezskeho zámku umenia a podnikania v Cieszyne.'),(40,10,'sk',10,'Vybrané práce budú prezentované na webových stránkach organizátorov, webových stránkach prehliadky (www.graduationprojects.eu), na výstave a v katalógu.'),(41,10,'sk',11,'Počet diel vybraných pre publikovanie i výstavu určí hodnotiaca komisia. Konečné rozhodnutie týkajúce sa množstva a výberu zverejnených prác majú redakcie jednotlivých časopisov.'),(42,10,'sk',12,'Zápis z rokovania hodnotiacej komisie obdržia účastníci prostredníctvom e-mailu najneskôr 5.01.2011, kedy bude výsledok zverejnený aj na internetových stránkach prehliadky.'),(43,10,'sk',13,'Organizátori sa s autormi vybraných prác dohodnú na spôsobe ich prezentácie v tlačovej podobe a na výstave prostredníctvom e-mailu.'),(44,10,'sk',14,'Vo všetkých ostatných prípadoch, ktoré nie sú uvedené v týchto pravidlách, majú posledné slovo organizátori prehliadky.'),(45,10,'sk',15,'Všetky otázky týkajúce sa prehliadky vybavuje v anglickom jazyku komisár Tomasz Budzyn na adrese: review@graduationprojects.eu alebo prostredníctvom kontaktného formulára.'),(49,9,'pl',1,'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec ornare arcu et elit facilisis in faucibus lacus viverra. Fusce a tincidunt dui. Vestibulum et ultricies velit. Aliquam pulvinar accumsan erat, a gravida eros ultrices in. Donec cursus rutrum erat non tempor. Sed lacinia ultricies odio, et dictum eros convallis eu. Sed imperdiet purus eu erat faucibus aliquet.'),(50,9,'pl',2,'Morbi ut neque augue, ut malesuada nulla. Lorem ipsum dolor sit amet, consectetur adipiscing elit. In sed lacus convallis orci pharetra suscipit. Aliquam sodales gravida tellus ac euismod. Pellentesque id malesuada orci. Vivamus vehicula felis id lorem suscipit a iaculis metus commodo. Ut suscipit sodales scelerisque. Quisque cursus vehicula sapien ut fringilla. In blandit porta adipiscing. Sed egestas leo vel lectus dictum consectetur. Suspendisse ipsum libero, ornare in aliquet vitae, pulvinar a neque.');
/*!40000 ALTER TABLE `regulations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `schools`
--

DROP TABLE IF EXISTS `schools`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `schools` (
  `school_id` smallint(6) NOT NULL AUTO_INCREMENT,
  `school_name` varchar(60) NOT NULL,
  PRIMARY KEY (`school_id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `schools`
--

LOCK TABLES `schools` WRITE;
/*!40000 ALTER TABLE `schools` DISABLE KEYS */;
INSERT INTO `schools` VALUES (1,'Akademia Sztuk Pięknych w Krakowie'),(26,'Akademia Wzornictwa Przemysłowego'),(27,'Akademia Sztuk Pięknych w Kielcach'),(28,'Akademia Sztuk Pięknych w Warszawie');
/*!40000 ALTER TABLE `schools` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `settings` (
  `current_edition` smallint(6) NOT NULL DEFAULT '0',
  `template_default` smallint(6) DEFAULT NULL,
  `max_file_size` int(11) NOT NULL,
  `date_format` varchar(10) NOT NULL,
  `max_files` smallint(6) NOT NULL,
  `work_start_date` int(11) NOT NULL,
  `work_end_date` int(11) NOT NULL,
  `application_deadline` int(11) NOT NULL,
  `result_date` int(11) NOT NULL,
  PRIMARY KEY (`current_edition`),
  KEY `template_default` (`template_default`),
  CONSTRAINT `settings_ibfk_1` FOREIGN KEY (`current_edition`) REFERENCES `editions` (`edition_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `settings_ibfk_2` FOREIGN KEY (`template_default`) REFERENCES `template_settings` (`template_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings`
--

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
INSERT INTO `settings` VALUES (10,1,1048576,'d.m.Y',10,1291158000,1322607600,1323126000,1325977200);
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `template_settings`
--

DROP TABLE IF EXISTS `template_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `template_settings` (
  `template_id` smallint(6) NOT NULL AUTO_INCREMENT,
  `template_name` varchar(20) NOT NULL,
  PRIMARY KEY (`template_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `template_settings`
--

LOCK TABLES `template_settings` WRITE;
/*!40000 ALTER TABLE `template_settings` DISABLE KEYS */;
INSERT INTO `template_settings` VALUES (1,'simple');
/*!40000 ALTER TABLE `template_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `nick` varchar(50) NOT NULL,
  `password` char(64) NOT NULL,
  `name` char(150) NOT NULL,
  `surname` char(200) NOT NULL,
  `address` varchar(200) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `email` varchar(35) NOT NULL,
  `show_email` tinyint(1) DEFAULT '0',
  `role` varchar(20) NOT NULL DEFAULT 'user',
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'zefiryn','d033e22ae348aeb5660fc2140aec35850c4da997','Artur','Jewuła','','','zefiryn@jewula.net',0,'admin'),(3,'kikut','c238c345f4589326ebb393267235d873acc8f892','Jan','Dworak','30-499 Kraków, ul. Zakarczmie 1a','+48 506939747','arturro2@poczta.fm',1,'user'),(4,'juror','d20b6c04435dabb4deb1423c5cf1dcff92527b99','','','','','dzieci_ciemnosci@poczta.fm',0,'juror'),(5,'mani','c29222e98de6437a383e8d946a9b202298e0aa3a','Jan','Kowal','32-800 Jasło, ul. Wilka 23/47','+48 500123456','artur@jewula.net',1,'user');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `work_types`
--

DROP TABLE IF EXISTS `work_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `work_types` (
  `work_type_id` smallint(6) NOT NULL AUTO_INCREMENT,
  `work_type_name` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`work_type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `work_types`
--

LOCK TABLES `work_types` WRITE;
/*!40000 ALTER TABLE `work_types` DISABLE KEYS */;
INSERT INTO `work_types` VALUES (1,'2d'),(2,'3d');
/*!40000 ALTER TABLE `work_types` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2011-03-13 17:46:30
