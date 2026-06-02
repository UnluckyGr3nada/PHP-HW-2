CREATE TABLE schools (
  id INT auto_increment,
  name VARCHAR(255) not null,
  PRIMARY KEY (id)
);  

CREATE TABLE students (
  id INT auto_increment,
  name VARCHAR(255) not null,
  school_id INT not null,
  PRIMARY KEY (id),
  FOREIGN KEY (school_id) REFERENCES schools(id)
);

CREATE TABLE sports (
  id INT auto_increment,
  name VARCHAR(255) not null,
  PRIMARY KEY (id)
);

CREATE TABLE student_sports (
  student_id INT not null,
  sport_id INT not null,
  PRIMARY KEY (student_id, sport_id),
  FOREIGN KEY (student_id) REFERENCES students(id),
  FOREIGN KEY (sport_id) REFERENCES sports(id)
);

-- Correct syntax for multi-row insertions
INSERT INTO schools (name) VALUES ('École A'), ('École B'), ('École C');
INSERT INTO sports (name) VALUES ('boxe'), ('judo'), ('football'), ('natation'), ('cyclisme');