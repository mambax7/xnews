#
# Table structure for table `xnews_import`
#

CREATE TABLE xnews_import (
  import_id int(10) unsigned NOT NULL auto_increment,
  import_name varchar(50) NOT NULL default '',
  import_dir varchar(50) NOT NULL default '',
  import_done tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (import_id)
) ENGINE=MyISAM CHARACTER SET 'utf8' COLLATE 'utf8_general_ci';
