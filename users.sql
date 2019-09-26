CREATE TABLE users (
  uName varchar(10),
  fromRegion varchar(80) not null,
  toRegion varchar(80) not null,
  PRIMARY KEY (uName)
);

INSERT INTO users VALUES ('admin', 'Auckland, Wellington', 'GMT: Dublin, Edinburgh, Lisbon, London');
INSERT INTO users VALUES ('user', 'Alaska', 'Hawaii');
