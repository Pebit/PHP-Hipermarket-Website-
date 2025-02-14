-- create the database
-- CREATE DATABASE if0_37692842_market_jonsi CHARACTER SET=utf8mb4;

-- create the user and grant privileges
-- CREATE USER 'jonsiuser'@'localhost' IDENTIFIED BY 'jonsipass';
-- GRANT ALL ON if0_37692842_market_jonsi.* TO 'jonsiuser'@'localhost';

-- create the user and grant privileges
-- CREATE USER 'jonsiuser'@'127.0.0.1' IDENTIFIED BY 'jonsipass';
-- GRANT ALL ON if0_37692842_market_jonsi.* TO 'jonsiuser'@'127.0.0.1';

-- if you run the commans from phpmyadmin, comment the next line
-- USE if0_37692842_market_jonsi;

-- this table is used to keep track of the migrations that have been run
CREATE TABLE migrations (
    id INTEGER AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(128) NOT NULL UNIQUE,
    date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    
) ENGINE=InnoDB CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- create the tables
CREATE TABLE user_roles (
    role_id INTEGER AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(128) UNIQUE
    
) ENGINE=InnoDB CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE users (
   user_id INTEGER AUTO_INCREMENT PRIMARY KEY,
   first_name VARCHAR(128) NOT NULL,
   last_name VARCHAR(128),
   email VARCHAR(128) UNIQUE NOT NULL,
   password VARCHAR(128) NOT NULL,
   role_id INTEGER NOT NULL,
   credits INTEGER DEFAULT 0,
   FOREIGN KEY(role_id) REFERENCES user_roles(role_id) ON DELETE RESTRICT
    
) ENGINE=InnoDB CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

create table purchases (
    purchase_id INTEGER AUTO_INCREMENT PRIMARY KEY,
    user_id INTEGER NOT NULL,
    total_price DECIMAL(10,2),
    purchase_credits INTEGER DEFAULT 0,
    purchase_date DATE NOT NULL,
    status BOOLEAN NOT NULL DEFAULT 0,
    FOREIGN KEY(user_id) REFERENCES users(user_id) ON DELETE CASCADE
    
) ENGINE=InnoDB CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE items (
    item_id INTEGER AUTO_INCREMENT PRIMARY KEY,
    item_name VARCHAR(128),
    expiration_date DATE NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    stock INTEGER DEFAULT 0
    
) ENGINE=InnoDB CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE sold_items (
    item_id INTEGER NOT NULL,
    purchase_id INTEGER NOT NULL,
    amount INTEGER NOT NULL,
    PRIMARY KEY (item_id, purchase_id),
    FOREIGN KEY(item_id) REFERENCES items(item_id) ON DELETE RESTRICT,
    FOREIGN KEY(purchase_id) REFERENCES purchases(purchase_id) ON DELETE CASCADE
    
) ENGINE=InnoDB CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- insert the default roles
INSERT INTO user_roles (role_id, name) VALUES (1, 'admin');
INSERT INTO user_roles (role_id, name) VALUES (2, 'user');
INSERT INTO user_roles (role_id, name) VALUES (3, 'guest');

-- insert the default items
INSERT INTO items (item_name, price) VALUES ('store_credit', -0.05);

-- insert the default users
INSERT INTO users (first_name, last_name, email, password, role_id, credits) VALUES ('admin', 'admin', 'admin@admin.com', 'admin', 1, 0);
INSERT INTO users (first_name, last_name, email, password, role_id, credits) VALUES ('user', 'user', 'user@user.com', 'user', 2, 0);

-- insert some items
INSERT INTO items (item_name, price, stock, expiration_date) VALUES ('Lapte Din Inima Ardealului 3.5% Grasime 1L - Napolact', 8.49, 90, '2024-11-24');
INSERT INTO items (item_name, price, stock, expiration_date) VALUES ('Bomboane 400g - Toffifee', 33.06, 25, '2025-02-20');
INSERT INTO items (item_name, price, stock, expiration_date) VALUES ('Bautura Racoritoare Necaronatata Cu Piure De Mere Verzi 1L - Tymbark', 33.06, 30, '2026-12-13');
INSERT INTO items (item_name, price, stock, expiration_date) VALUES ('Iaurt De Casa 400g - Diami', 33.06, 900, '2025-11-21');
INSERT INTO items (item_name, price, stock, expiration_date) VALUES ('Aripi De Pui Secționate 630g - Cocorico', 33.06, 45, '2024-11-23');

-- insert the migration
INSERT INTO migrations (name) VALUES ('create_db_1');