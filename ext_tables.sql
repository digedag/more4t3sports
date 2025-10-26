CREATE TABLE tx_cfcleague_games (
	newspreview int(11) DEFAULT '0' NOT NULL,
	newsreport int(11) DEFAULT '0' NOT NULL,
	newsrels int(11) DEFAULT '0' NOT NULL
);

CREATE TABLE tx_more4t3sports_newsrel (
	uid int(11) NOT NULL auto_increment,
	tstamp int(11) DEFAULT '0' NOT NULL,
	crdate int(11) DEFAULT '0' NOT NULL,
	pid int(11) DEFAULT '0' NOT NULL,
	cruser_id int(11) DEFAULT '0' NOT NULL,
	deleted tinyint(4) DEFAULT '0' NOT NULL,
	sorting int(10) DEFAULT '0' NOT NULL,

	parentid int(11) DEFAULT '0' NOT NULL,
	parenttable varchar(50) DEFAULT '' NOT NULL,

	category int(11) DEFAULT '0' NOT NULL,
	news int(11) DEFAULT '0' NOT NULL,
	uri text,

	PRIMARY KEY (uid),
	KEY parent (pid)
);
