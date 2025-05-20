-- Create database
CREATE DATABASE IF NOT EXISTS smart_faq_chatbot;
USE smart_faq_chatbot;

-- Users table for admin authentication
CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_login TIMESTAMP NULL
);

-- FAQs table
CREATE TABLE IF NOT EXISTS faqs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    question TEXT NOT NULL,
    answer TEXT NOT NULL,
    category VARCHAR(50) NOT NULL,
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id)
);

-- Chat logs table
CREATE TABLE IF NOT EXISTS chat_logs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_session_id VARCHAR(100) NOT NULL,
    question TEXT NOT NULL,
    answer TEXT NOT NULL,
    is_ai_response BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Categories table
CREATE TABLE IF NOT EXISTS categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50) UNIQUE NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert default admin user (password: admin123)
INSERT INTO users (username, password, email) VALUES 
('admin', '$2y$10$8K1p/a0dR1Ux5Y5Y5Y5Y5O5Y5Y5Y5Y5Y5Y5Y5Y5Y5Y5Y5Y5Y5Y', 'admin@university.edu');

-- Insert some default categories
INSERT INTO categories (name, description) VALUES
('General', 'General university information'),
('Academics', 'Academic-related questions'),
('Admissions', 'Admission process and requirements'),
('Financial Aid', 'Financial aid and scholarships'),
('Campus Life', 'Campus facilities and student life'); 