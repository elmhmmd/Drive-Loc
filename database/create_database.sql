-- Create the database
CREATE DATABASE IF NOT EXISTS drive_loc;
USE drive_loc;

-- Create roles table
CREATE TABLE roles (
    role_id INT PRIMARY KEY AUTO_INCREMENT,
    role_name VARCHAR(255) NOT NULL
);

-- Create categories table
CREATE TABLE categories (
    category_id INT PRIMARY KEY AUTO_INCREMENT,
    category_name VARCHAR(255) NOT NULL
);

-- Create users table
CREATE TABLE users (
    user_id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role_id INT,
    FOREIGN KEY (role_id) REFERENCES roles(role_id)
);

-- Create reservations table
CREATE TABLE reservations (
    reservation_id INT PRIMARY KEY AUTO_INCREMENT,
    from_date DATETIME NOT NULL,
    to_date DATETIME NOT NULL,
    location VARCHAR(255) NOT NULL,
    client_id INT,
    vehicle_id INT,
    FOREIGN KEY (client_id) REFERENCES users(user_id)
);

-- Create vehicles table
CREATE TABLE vehicles (
    vehicle_id INT PRIMARY KEY AUTO_INCREMENT,
    vehicle_name VARCHAR(100) NOT NULL,
    model VARCHAR(100) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    category_id INT,
    reservation_id INT UNIQUE,
    FOREIGN KEY (category_id) REFERENCES categories(category_id),
    FOREIGN KEY (reservation_id) REFERENCES reservations(reservation_id)
);

-- Add foreign key to reservations after vehicles table is created
ALTER TABLE reservations
ADD FOREIGN KEY (vehicle_id) REFERENCES vehicles(vehicle_id);

-- Create reviews table
CREATE TABLE reviews (
    review_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    content TEXT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);

-- Insert some initial data
INSERT INTO roles (role_name) VALUES 
('admin'),
('client');

INSERT INTO categories (category_name) VALUES 
('Luxury'),
('Sports'),
('SUV'),
('Electric');

ALTER TABLE reservations
ADD COLUMN pickup_location VARCHAR(255) NOT NULL AFTER location,
ADD COLUMN return_location VARCHAR(255) NOT NULL AFTER pickup_location;