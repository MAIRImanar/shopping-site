-- Création de la base de données
CREATE DATABASE IF NOT EXISTS shopping_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE shopping_db;

-- Table des catégories
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table des produits
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(200) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    image VARCHAR(255),
    category_id INT,
    stock INT DEFAULT 0,
    featured BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
);

-- Table des utilisateurs
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100),
    address TEXT,
    phone VARCHAR(20),
    is_admin BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table des commandes
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    total DECIMAL(10, 2) NOT NULL,
    status ENUM('pending', 'processing', 'shipped', 'delivered', 'cancelled') DEFAULT 'pending',
    shipping_address TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Table des détails de commande
CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- Insertion de catégories d'exemple
INSERT INTO categories (name, description) VALUES
('Électronique', 'Smartphones, ordinateurs, accessoires électroniques'),
('Vêtements', 'Mode homme, femme et enfant'),
('Maison', 'Décoration, meubles, électroménager'),
('Sports', 'Équipements sportifs et fitness');

-- Insertion de produits d'exemple
INSERT INTO products (name, description, price, image, category_id, stock, featured) VALUES
('Smartphone XPhone 14', 'Dernier modèle avec écran OLED 6.5" et caméra 48MP', 699.99, 'smartphone.jpg', 1, 50, TRUE),
('Ordinateur Portable ProBook', 'Intel i7, 16GB RAM, SSD 512GB, écran 15.6"', 1299.99, 'laptop.jpg', 1, 30, TRUE),
('T-shirt Premium', 'Coton 100%, disponible en plusieurs couleurs', 29.99, 'tshirt.jpg', 2, 100, FALSE),
('Jean Slim Fit', 'Denim de qualité supérieure, coupe moderne', 79.99, 'jeans.jpg', 2, 75, FALSE),
('Canapé 3 Places', 'Design moderne, tissu premium, très confortable', 899.99, 'sofa.jpg', 3, 15, TRUE),
('Lampe de Bureau LED', 'Éclairage réglable, design minimaliste', 49.99, 'lamp.jpg', 3, 60, FALSE),
('Tapis de Yoga', 'Antidérapant, épaisseur 6mm, écologique', 34.99, 'yoga.jpg', 4, 80, FALSE),
('Haltères 10kg', 'Paire d\'haltères réglables pour fitness', 89.99, 'dumbbells.jpg', 4, 40, FALSE);

-- Création d'un utilisateur admin par défaut (mot de passe: admin123)
INSERT INTO users (username, email, password, full_name, is_admin) VALUES
('admin', 'admin@shop.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrateur', TRUE);
