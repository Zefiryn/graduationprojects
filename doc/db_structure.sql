DROP TABLE IF EXISTS files, applications, template_settings, work_types, degrees, schools, users, 
regulations, faq, localizations, editions, settings;

CREATE TABLE editions (
	edition_id smallint(6) NOT NULL AUTO_INCREMENT,
	edition_name char(10) NOT NULL,
	PRIMARY KEY (edition_id)
) ENGINE=INNODB DEFAULT CHARSET=utf8;

CREATE TABLE localizations (
	item_id int NOT NULL AUTO_INCREMENT,
	name varchar(60) not null,
	lang_code char(4) not null,
	text text not null,
	PRIMARY KEY (item_id)
) ENGINE=INNODB DEFAULT CHARSET=utf8;

CREATE TABLE ragulations (
	paragraph_id int NOT NULL AUTO_INCREMENT,
	edition_id smallint(6) not null,
	regulation_lang char(4) not null,
	paragraph_no smallint(6) not null,
	paragraph_text text not null,
	PRIMARY KEY (paragraph_id),
	FOREIGN KEY(edition_id)
		REFERENCES editions(edition_id)
		ON DELETE CASCADE ON UPDATE CASCADE,
) ENGINE=INNODB DEFAULT CHARSET=utf8;

CREATE TABLE faq (
	faq_id int NOT NULL AUTO_INCREMENT,
	faq_lang char(4) not null,
	faq_question varchar(300) not null,
	faq_answer text not null,
	PRIMARY KEY (faq_id)
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
	PRIMARY KEY (user_id)
)ENGINE=INNODB DEFAULT CHARSET=utf8;

CREATE TABLE schools(
	school_id smallint(6) NOT NULL AUTO_INCREMENT,
	school_name varchar(60) NOT NULL,
	PRIMARY KEY (school_id)
)ENGINE=INNODB DEFAULT CHARSET=utf8;

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

CREATE TABLE template_settings(
	template_id smallint(6) NOT NULL AUTO_INCREMENT,
	template_name varchar(20) NOT NULL,
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
	news_title varchar(100) not null,
	news_text text not null,
	added int not null default current_timestamp,
	current_edition smallint(6),
	PRIMARY KEY(current_edition),
	FOREIGN KEY(current_edition)
		REFERENCES editions(edition_id)
		ON DELETE CASCADE ON UPDATE CASCADE,
) ENGINE=INNODB DEFAULT CHARSET=utf8;

CREATE TABLE jurors(
	juror_id int not null auto_increment,
	user_id int(6) not null,
	max_point smallint not null,
	country char(2) not null,
	PRIMARY KEY(juror_id),
	FOREIGN KEY(user_id)
		REFERENCES users(user_id)
		ON DELETE CASCADE ON UPDATE CASCADE,
) ENGINE=INNODB DEFAULT CHARSET=utf8;