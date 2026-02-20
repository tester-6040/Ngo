CREATE DATABASE IF NOT EXISTS ngo_donation CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE ngo_donation;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    email VARCHAR(120) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user', 'orphanage') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_users_role (role)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS donations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    orphanage_id INT NULL,
    title VARCHAR(160) NOT NULL,
    description TEXT NOT NULL,
    quantity INT NOT NULL,
    status ENUM('pending', 'assigned', 'completed') NOT NULL DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_donations_status (status),
    INDEX idx_donations_user_id (user_id),
    INDEX idx_donations_orphanage_id (orphanage_id),
    CONSTRAINT fk_donor FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    CONSTRAINT fk_orphanage FOREIGN KEY (orphanage_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- Default admin login: admin@ngo.local / admin123
INSERT INTO users (name, email, password_hash, role)
VALUES ('Platform Admin', 'admin@ngo.local', '$2y$12$oCJkKoXh.2vR7UrO1hGYZ.bS9.IENCriYj.I1n9pR4DwPdLIntcEW', 'admin')
ON DUPLICATE KEY UPDATE email = VALUES(email);
