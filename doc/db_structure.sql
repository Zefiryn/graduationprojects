DROP TABLE IF EXISTS about, jurors, news_files, news_details, news, settings,
template_settings, diploma_files, result_files, diploma_fields, result_fields, diplomas, results,
fields, files, applications, schools, users, faq, regulations, localizations, captions, 
work_types, degrees, languages, editions;  

CREATE TABLE editions (
	edition_id smallint(6) NOT NULL AUTO_INCREMENT,
	edition_name char(10) NOT NULL,
	PRIMARY KEY (edition_id)
) ENGINE=INNODB DEFAULT CHARSET=utf8;

CREATE TABLE press (
	element_id smallint(6) NOT NULL AUTO_INCREMENT,
	element_path varchar(100) NOT NULL,
	element_type varchar(30) NOT NULL,
	element_description varchar(255) NOT NULL,
	edition_id smallint(6),
	PRIMARY KEY (element_id),
	FOREIGN KEY(edition_id)
		REFERENCES editions(edition_id)
		ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=INNODB DEFAULT CHARSET=utf8;

CREATE TABLE languages (
	lang_id int NOT NULL AUTO_INCREMENT,
	name varchar(60) not null,
	lang_code char(4) not null,
	PRIMARY KEY (lang_id)
) ENGINE=INNODB DEFAULT CHARSET=utf8;

CREATE TABLE pages (
  page_id smallint(6) NOT NULL AUTO_INCREMENT,
  page_name varchar(100) NOT NULL,
  lang_id int NOT NULL,
  content TEXT NOT NULL,
  PRIMARY KEY (page_id),
  FOREIGN KEY(lang_id)
    REFERENCES languages(lang_id)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=INNODB DEFAULT CHARSET=utf8;

CREATE TABLE degrees(
	degree_id smallint(6) NOT NULL AUTO_INCREMENT,
	degree_name char(13) NOT NULL,
	PRIMARY KEY (degree_id)
)ENGINE=INNODB DEFAULT CHARSET=utf8;

CREATE TABLE work_types(
	work_type_id smallint(6) NOT NULL AUTO_INCREMENT,
	work_type_name varchar(10),
	PRIMARY KEY (work_type_id)
)ENGINE=INNODB DEFAULT CHARSET=utf8;

CREATE TABLE captions (
	caption_id int NOT NULL AUTO_INCREMENT,
	name varchar(60) not null,
	PRIMARY KEY (caption_id)
)ENGINE=INNODB DEFAULT CHARSET=utf8;

CREATE TABLE localizations (
	item_id int NOT NULL AUTO_INCREMENT,
	caption_id int not null,
	lang_id int not null,
	text text not null,
	PRIMARY KEY (item_id),
	FOREIGN KEY(lang_id)
		REFERENCES languages(lang_id)
		ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY(caption_id)
		REFERENCES captions(caption_id)
		ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=INNODB DEFAULT CHARSET=utf8;

CREATE TABLE regulations (
	paragraph_id int NOT NULL AUTO_INCREMENT,
	lang_id int not null,
	paragraph_no smallint(6) not null,
	paragraph_text text not null,
	PRIMARY KEY (paragraph_id),
	FOREIGN KEY(lang_id)
		REFERENCES languages(lang_id)
		ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=INNODB DEFAULT CHARSET=utf8;

CREATE TABLE faq (
	faq_id int NOT NULL AUTO_INCREMENT,
	lang_id int not null,
	faq_question varchar(300) not null,
	faq_answer text not null,
	position smallint not null,
	PRIMARY KEY (faq_id),
	FOREIGN KEY(lang_id)
		REFERENCES languages(lang_id)
		ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=INNODB DEFAULT CHARSET=utf8;

CREATE TABLE users(
	user_id int NOT NULL AUTO_INCREMENT,
	nick varchar(50) NOT NULL,
	password char(64) NOT NULL,
	name char(150) NOT NULL,
	surname char(200) NOT NULL,
	address varchar(200) NOT NULL,
	phone varchar(15) NOT NULL,
	email varchar(35) NOT NULL,
	show_email boolean DEFAULT FALSE,
	role varchar(20) NOT NULL DEFAULT 'user',
	juror_id int null,
	PRIMARY KEY (user_id)
)ENGINE=INNODB DEFAULT CHARSET=utf8;

CREATE TABLE schools(
	school_id smallint(6) NOT NULL AUTO_INCREMENT,
	school_name varchar(60) NOT NULL,
	PRIMARY KEY (school_id)
)ENGINE=INNODB DEFAULT CHARSET=utf8;


CREATE TABLE applications (
	application_id smallint(6) NOT NULL AUTO_INCREMENT,
	edition_id smallint(6),
	user_id int(6),
	country char(2) NOT NULL,
	school_id smallint(6),
	department varchar(60) NOT NULL,
	degree_id smallint(6),
	work_subject varchar(300) NOT NULL,
	work_type_id smallint(6),
	work_desc text NOT NULL,
	supervisor varchar(60) NOT NULL,
	supervisor_degree varchar(15) NOT NULL,
	graduation_time INT NOT NULL,	
	application_date INT NOT NULL,
	miniature VARCHAR(35),
	active tinyint(1) NOT NULL DEFAULT 1,
	PRIMARY KEY (application_id),
	FOREIGN KEY(edition_id)
		REFERENCES editions(edition_id)
		ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY(user_id)
		REFERENCES users(user_id)
		ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY(school_id)
		REFERENCES schools(school_id)
		ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY(degree_id)
		REFERENCES degrees(degree_id)
		ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY(work_type_id)
		REFERENCES work_types(work_type_id)
		ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=INNODB DEFAULT CHARSET=utf8;

CREATE TABLE files(
	file_id smallint(6) NOT NULL AUTO_INCREMENT,
	application_id smallint(6) NOT NULL,
	path varchar(150) NOT NULL,
	file_desc varchar(150),
	PRIMARY KEY (file_id),
	FOREIGN KEY(application_id)
		REFERENCES applications(application_id)
		ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=INNODB DEFAULT CHARSET=utf8;

CREATE TABLE fields (
	field_id smallint(6) NOT NULL AUTO_INCREMENT,
	field_name varchar(100) NOT NULL,
	PRIMARY KEY (field_id)
) ENGINE=INNODB DEFAULT CHARSET=utf8;

CREATE TABLE diplomas (
	diploma_id smallint(6) NOT NULL AUTO_INCREMENT,
	edition_id smallint(6),
	name char(150) NOT NULL,
	surname char(200) NOT NULL,
	email varchar(35) NOT NULL,
	country char(2) NOT NULL,
	degree_id smallint(6),
	work_type_id smallint(6),
	graduation_time INT,	
	supervisor varchar(60) NOT NULL,
	supervisor_degree varchar(15),
	PRIMARY KEY (diploma_id),
	FOREIGN KEY(edition_id)
		REFERENCES editions(edition_id)
		ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY(degree_id)
		REFERENCES degrees(degree_id)
		ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY(work_type_id)
		REFERENCES work_types(work_type_id)
		ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=INNODB DEFAULT CHARSET=utf8;

CREATE TABLE diploma_fields (
	diploma_field_id int not null auto_increment,
	diploma_id smallint(6) NOT NULL,
	lang_id int NOT NULL,
	field_id smallint(6) not null,
	entry text not null,
	PRIMARY KEY (diploma_field_id),
	FOREIGN KEY(diploma_id)
		REFERENCES diplomas(diploma_id)
		ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY(lang_id)
		REFERENCES languages(lang_id)
		ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY(field_id)
		REFERENCES fields(field_id)
		ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=INNODB DEFAULT CHARSET=utf8;

CREATE TABLE diploma_files(
	file_id smallint(6) NOT NULL AUTO_INCREMENT,
	diploma_id smallint(6) NOT NULL,
	path varchar(150) NOT NULL,
	file_desc varchar(150),
	PRIMARY KEY (file_id),
	FOREIGN KEY(diploma_id)
		REFERENCES diplomas(diploma_id)
		ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=INNODB DEFAULT CHARSET=utf8;

CREATE TABLE template_settings(
	template_id smallint(6) NOT NULL AUTO_INCREMENT,
	template_name varchar(20) NOT NULL,
	news_limit int not null,
	PRIMARY KEY (template_id)
)ENGINE=INNODB DEFAULT CHARSET=utf8;

CREATE TABLE settings(
	current_edition smallint(6),
	template_default smallint(6),
	max_file_size int NOT NULL,
	date_format varchar(10) NOT NULL,
	max_files smallint(6) NOT NULL,
	work_start_date int(11) NOT NULL,
	work_end_date int(11) NOT NULL,
	application_deadline int(11) NOT NULL,	
	result_date int(11) NOT NULL,
	PRIMARY KEY(current_edition),
	FOREIGN KEY(current_edition)
		REFERENCES editions(edition_id)
		ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY(template_default)
		REFERENCES template_settings(template_id)
		ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=INNODB DEFAULT CHARSET=utf8;

CREATE TABLE news(
	news_id int not null auto_increment,
	added int not null,
	PRIMARY KEY(news_id)
) ENGINE=INNODB DEFAULT CHARSET=utf8;

CREATE TABLE news_details(
	news_text_id int not null auto_increment,
	news_title varchar(100) not null,
	news_id int not null,
	news_text text not null,
	news_lead text not null,
	lang_id int not null,
	PRIMARY KEY(news_text_id),
	FOREIGN KEY(news_id)
		REFERENCES news(news_id)
		ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY(lang_id)
		REFERENCES languages(lang_id)
		ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=INNODB DEFAULT CHARSET=utf8;

CREATE TABLE news_files(
	news_file_id int not null auto_increment,
	news_id int not null,
	path varchar(300) not null,
	PRIMARY KEY(news_file_id),
	FOREIGN KEY(news_id)
		REFERENCES news(news_id)
		ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=INNODB DEFAULT CHARSET=utf8;


CREATE TABLE about(
	about_id int not null auto_increment,
	about_title varchar(300) not null,
	about_text text not null,
	lang_id int not null,
	PRIMARY KEY(about_id),
	FOREIGN KEY(lang_id)
		REFERENCES languages(lang_id)
		ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=INNODB DEFAULT CHARSET=utf8;

CREATE TABLE jurors(
	juror_id int not null auto_increment,
	juror_name varchar(255) not null,
	country char(2) not null,
	wage INT( 11 ) NOT NULL DEFAULT '1',
	PRIMARY KEY(juror_id)
) ENGINE=INNODB DEFAULT CHARSET=utf8;

CREATE TABLE stages (
	stage_id smallint(6) NOT NULL AUTO_INCREMENT,
	stage_name char(10) NOT NULL,
	stage_max_vote SMALLINT(6) NOT NULL,
	active tinyint default '1',
	PRIMARY KEY (stage_id)
) ENGINE=INNODB DEFAULT CHARSET=utf8;

CREATE TABLE votes (
	vote_id int NOT NULL AUTO_INCREMENT,
	stage_id smallint(6) not null,
	juror_id int not null,
	application_id smallint(6) not null,
	vote int not null,
	PRIMARY KEY (vote_id),
	FOREIGN KEY(stage_id)
		REFERENCES stages(stage_id)
		ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY(juror_id)
		REFERENCES jurors(juror_id)
		ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY(application_id)
		REFERENCES applications(application_id)
		ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=INNODB DEFAULT CHARSET=utf8;

CREATE TABLE disputes (
	dispute_id int NOT NULL AUTO_INCREMENT,
	user_id int(11) not null,
	application_id smallint(6) not null,
	PRIMARY KEY (dispute_id),
	FOREIGN KEY(user_id)
		REFERENCES users(user_id)
		ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY(application_id)
		REFERENCES applications(application_id)
		ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=INNODB DEFAULT CHARSET=utf8;