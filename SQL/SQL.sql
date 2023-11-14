CREATE DATABASE phonebook_api;

USE phonebook_api;

CREATE TABLE users (
    id BIGINT NOT NULL AUTO_INCREMENT,
    username VARCHAR(255) UNIQUE NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    PRIMARY KEY (id)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;

CREATE TABLE tokens (
    id BIGINT NOT NULL AUTO_INCREMENT,
    user_id BIGINT UNIQUE NOT NULL,
    token VARCHAR(255) NOT NULL,
    expiration_date DATETIME NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (user_id) REFERENCES users (id)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;

CREATE TABLE contacts (
    id BIGINT NOT NULL AUTO_INCREMENT,
    user_id BIGINT NOT NULL,
    name VARCHAR(255) NOT NULL,
    email UNIQUE VARCHAR(255),
    PRIMARY KEY (id),
    FOREIGN KEY (user_id) REFERENCES users (id)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;

CREATE TABLE phones (
    id BIGINT NOT NULL AUTO_INCREMENT,
    contact_id BIGINT NOT NULL,
    phonenumber VARCHAR(255) NOT NULL,
    description VARCHAR(255),
    PRIMARY KEY (id),
    FOREIGN KEY (contact_id) REFERENCES contacts (id)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;