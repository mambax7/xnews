#
# Table structure for table `xnews_import`
#

CREATE TABLE xnews_import (
  import_id   INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  import_name VARCHAR(50)      NOT NULL DEFAULT '',
  import_dir  VARCHAR(50)      NOT NULL DEFAULT '',
  import_done TINYINT(1)       NOT NULL DEFAULT '1',
  PRIMARY KEY (import_id)
)
  ENGINE = MyISAM
  CHARACTER SET 'utf8'
  COLLATE 'utf8_general_ci';
