#
# Table structure for table 'tx_importr_domain_model_import'
#
CREATE TABLE tx_importr_domain_model_import (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,

	strategy int(11) unsigned DEFAULT '0' NOT NULL,
	filepath varchar(255) DEFAULT '' NOT NULL,
	starttime int(11) unsigned DEFAULT '0' NOT NULL,
	endtime int(11) unsigned DEFAULT '0' NOT NULL,
	pointer int(11) unsigned DEFAULT '0' NOT NULL,
	amount int(11) unsigned DEFAULT '0' NOT NULL,

	inserted int(11) unsigned DEFAULT '0' NOT NULL,
	updated int(11) unsigned DEFAULT '0' NOT NULL,
	ignored int(11) unsigned DEFAULT '0' NOT NULL,
	unknowns int(11) unsigned DEFAULT '0' NOT NULL,
	errors int(11) unsigned DEFAULT '0' NOT NULL,

	deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid),
	KEY parent (pid)
);

#
# Table structure for table 'tx_importr_domain_model_strategy'
#
CREATE TABLE tx_importr_domain_model_strategy (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,

	title varchar(255) DEFAULT '' NOT NULL,
	configuration text NOT NULL,
	resources text NOT NULL,
	targets text NOT NULL,
	general tinyint(4) unsigned DEFAULT '0' NOT NULL,

	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid),
	KEY parent (pid)
);