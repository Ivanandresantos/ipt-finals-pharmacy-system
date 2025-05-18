// SQL Script to create necessary tables - pharmacy_db.sql
CREATE DATABASE IF NOT EXISTS pharmacy_db;
USE pharmacy_db;

-- Users table
CREATE TABLE IF NOT EXISTS users (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Medications table
CREATE TABLE IF NOT EXISTS medications (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    quantity INT NOT NULL,
    manufacturer VARCHAR(100) NOT NULL,
    expiry_date DATE NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Insert sample data
INSERT INTO medications (name, description, price, quantity, manufacturer, expiry_date) VALUES
('Paracetamol 500mg', 'Pain reliever and fever reducer', 5.99, 100, 'PharmaCorp', '2025-12-31'),
('Amoxicillin 250mg', 'Antibiotic for bacterial infections', 12.50, 50, 'MediPharma', '2026-06-30'),
('Loratadine 10mg', 'Antihistamine for allergy relief', 8.75, 75, 'AllergyRelief Inc', '2025-09-15'),
('Ibuprofen 400mg', 'NSAID for pain and inflammation', 6.50, 120, 'PainAway Labs', '2026-03-22'),
('Omeprazole 20mg', 'Proton pump inhibitor for acid reflux', 15.99, 30, 'DigestCare', '2025-11-10');