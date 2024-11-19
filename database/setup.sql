DROP DATABASE IF EXISTS contacts_app;

CREATE DATABASE contacts_app;

USE contacts_app;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL
);

CREATE TABLE contacts(
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    phone_number VARCHAR(100) NOT NULL,
    email VARCHAR(255) NULL
);

INSERT INTO users (name, email, password) VALUES ("Test", "test@test.com", "test123");

INSERT INTO contacts (name, phone_number, email) VALUES
    ("Test", "0148592469", NULL),
    ("Kavin", "015734873", "kavin@kavin.com");
