CREATE TABLE gcdb_spams (
  spam_ID bigint(20) NOT NULL auto_increment,
  spam_value varchar(200) NOT NULL default '',
  spam_type enum('name','email','text','ip') NOT NULL default 'text',  
  PRIMARY KEY  (spam_ID)
);

CREATE TABLE gcdb_x (
  x_ID bigint(20) NOT NULL auto_increment,
  post_ID bigint(20) NOT NULL,
  x_name varchar(200) NOT NULL,  
  PRIMARY KEY  (x_ID),
  UNIQUE KEY x_name (x_name)
);
