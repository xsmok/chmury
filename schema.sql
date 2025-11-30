-- Schemat bazy danych dla TODO App
-- Wykonaj te komendy w Azure Cloud Shell po polaczeniu z MySQL

USE tododb;

CREATE TABLE todos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    is_completed TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
