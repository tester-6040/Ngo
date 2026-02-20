CREATE DATABASE IF NOT EXISTS ngo_donation CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE ngo_donation;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('admin','user','orphanage') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_admin_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS orphanages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL UNIQUE,
    organization_name VARCHAR(180) NOT NULL,
    address TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_orphanage_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS donations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    orphanage_user_id INT NULL,
    description TEXT NOT NULL,
    quantity INT NOT NULL,
    pickup_address TEXT NOT NULL,
    status ENUM('pending','accepted','rejected') NOT NULL DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_donation_donor FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    CONSTRAINT fk_donation_orphanage_user FOREIGN KEY (orphanage_user_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_donation_status (status)
) ENGINE=InnoDB;

-- default admin: admin@ngo.local / admin12345
INSERT INTO users (name, email, password_hash, role)
VALUES ('Platform Admin', 'admin@ngo.local', '$2y$12$00zkGXX3/0v3Nq1aQWrT1uKfeHcMkB8or4q5DxyyYuYBtRy7AE60i', 'admin')
ON DUPLICATE KEY UPDATE email = VALUES(email);

INSERT INTO admins (user_id)
SELECT id FROM users WHERE email = 'admin@ngo.local'
ON DUPLICATE KEY UPDATE user_id = user_id;
