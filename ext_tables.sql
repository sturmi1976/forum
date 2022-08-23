CREATE TABLE tx_forum_domain_model_category (
	category varchar(255) NOT NULL DEFAULT '',
        url_segment varchar(255) DEFAULT '' NOT NULL,
);

CREATE TABLE tx_forum_domain_model_forum (
	forum varchar(255) NOT NULL DEFAULT '',
        description text NOT NULL DEFAULT '',
	category int(11) unsigned DEFAULT '0',
        url_segment varchar(255) DEFAULT '' NOT NULL,
);

CREATE TABLE tx_forum_domain_model_threads (
	title varchar(255) NOT NULL DEFAULT '',
        text text NOT NULL DEFAULT '',
	category int(11) unsigned DEFAULT '0',
        forum int(11) unsigned DEFAULT '0',
        url_segment varchar(255) DEFAULT '' NOT NULL, 
        user_id int(11) unsigned DEFAULT '0',
        klicks int(11) unsigned DEFAULT '0',
); 

CREATE TABLE tx_forum_domain_model_thread (
	title varchar(255) NOT NULL DEFAULT '',
        text text NOT NULL DEFAULT '',
	category int(11) unsigned DEFAULT '0',
        forum int(11) unsigned DEFAULT '0',
        url_segment varchar(255) DEFAULT '' NOT NULL, 
        user_id int(11) unsigned DEFAULT '0', 
        klicks int(11) unsigned DEFAULT '0', 
);

CREATE TABLE tx_forum_domain_model_topic (
        title varchar(255) NOT NULL DEFAULT '',
        text text NOT NULL DEFAULT '',
	category int(11) unsigned DEFAULT '0',
        forum int(11) unsigned DEFAULT '0',
        thread int(11) unsigned DEFAULT '0', 
        user_id int(11) unsigned DEFAULT '0', 
); 

CREATE TABLE tx_forum_domain_model_profilbilder (
        profilbild int(11) unsigned NOT NULL default '0',
        user_id int(11) unsigned NOT NULL default '0',
);

CREATE TABLE fe_users ( 
        username_path varchar(255) NOT NULL DEFAULT '', 
        birth_day varchar(255) NOT NULL DEFAULT '', 
        profilbild tinytext,
        user_hash_activated varchar(255) NOT NULL DEFAULT '',
);

CREATE TABLE fe_groups ( 
        color varchar(255) NOT NULL DEFAULT '', 
);

