

CREATE TABLE ecoles (
  id INT  auto_increment,
  name VARCHAR(255) not null,
  PRIMARY KEY (id)

);  

CREATE TABLE eleves (
  id INT auto_increment,
  name VARCHAR(255) not null,
  school_id INT not null,
  PRIMARY KEY (id),
  FOREIGN KEY (school_id) REFERENCES ecoles(id)
);


CREATE TABLE sports (
  id INT auto_increment,
  name VARCHAR(255) not null,
  PRIMARY KEY (id)
);

CREATE TABLE eleves_sports (
  eleves_id INT not null,
  sport_id INT not null,
  PRIMARY KEY (eleves_id, sport_id),
  FOREIGN KEY (eleves_id) REFERENCES eleves(id),
  FOREIGN KEY (sport_id) REFERENCES sports(id)
);


-- Insertion des données dans les tables

INSERT INTO ecoles (name) VALUES ('ecoles A', 'ecoles B', 'ecoles C');

INSERT INTO sports (name) VALUES ('boxe', 'judo', 'football','natation', 'cyclisme');