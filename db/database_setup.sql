CREATE DATABASE IF NOT EXISTS neighbor_complaints;
USE neighbor_complaints;

-- ----------------------------
-- Apartments
-- ----------------------------
CREATE TABLE IF NOT EXISTS apartments (
  id INT NOT NULL AUTO_INCREMENT,
  name VARCHAR(100) NOT NULL,
  floor INT NOT NULL,
  block VARCHAR(50) NOT NULL,
  created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- ----------------------------
-- Residents
-- ----------------------------
CREATE TABLE IF NOT EXISTS residents (
  id INT NOT NULL AUTO_INCREMENT,
  first_name VARCHAR(100) NOT NULL,
  last_name VARCHAR(100) NOT NULL,
  phone VARCHAR(15) DEFAULT NULL,
  apartment_id INT DEFAULT NULL,
  email VARCHAR(50) DEFAULT NULL,
  joining_date DATE DEFAULT NULL,
  password VARCHAR(255) NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  role ENUM('resident','admin') NOT NULL DEFAULT 'resident',
  PRIMARY KEY (id),
  UNIQUE KEY email (email),
  KEY fk_resident_apartment (apartment_id),
  CONSTRAINT fk_resident_apartment 
    FOREIGN KEY (apartment_id) REFERENCES apartments(id)
    ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- ----------------------------
-- Complaints
-- ----------------------------
CREATE TABLE IF NOT EXISTS complaints (
  id INT NOT NULL AUTO_INCREMENT,
  resident_id INT NOT NULL,
  title VARCHAR(150) NOT NULL,
  category VARCHAR(50) NOT NULL,
  description TEXT NOT NULL,
  location VARCHAR(100) DEFAULT NULL,
  incident_date DATE NOT NULL,
  priority ENUM('Low','Medium','High') DEFAULT 'Medium',
  attachment VARCHAR(255) DEFAULT NULL,
  status ENUM('Pending','In Progress','Resolved','Rejected') DEFAULT 'Pending',
  comment TEXT,
  created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY resident_id (resident_id),
  CONSTRAINT complaints_ibfk_1 
    FOREIGN KEY (resident_id) REFERENCES residents(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
