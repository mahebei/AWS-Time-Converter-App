CREATE TABLE users (
  uName varchar(10),
  fromCity varchar(80) not null,
  toCity varchar(80) not null,
  PRIMARY KEY (uname)
);

INSERT INTO users VALUES ('admin', 'Auckland, Wellington', 'GMT: Dublin, Edinburgh, Lisbon, London');
INSERT INTO users VALUES ('user', 'Alaska', 'Hawaii');
