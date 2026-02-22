-- ============================================
-- CREAR BASE DE DATOS
-- ============================================

CREATE DATABASE IF NOT EXISTS filmhub_api_db
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE filmhub_api_db;

-- ============================================
-- TABLA USERS (Admin y Clientes)
-- ============================================

CREATE TABLE users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    nombre VARCHAR(150) NOT NULL,
    apellido_paterno VARCHAR(100) NULL,
    apellido_materno VARCHAR(100) NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,

    role ENUM('admin','cliente') NOT NULL DEFAULT 'cliente',
    status ENUM('activo','inactivo') NOT NULL DEFAULT 'activo',

    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- ============================================
-- TABLA PELICULAS
-- ============================================

CREATE TABLE peliculas (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    nombre VARCHAR(150) NOT NULL,
    genero VARCHAR(100) NOT NULL,
    descripcion TEXT NOT NULL,
    imagen VARCHAR(255) NULL,
    link_trailer VARCHAR(255) NULL,

    estado ENUM('activa','inactiva') NOT NULL DEFAULT 'activa',

    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- ============================================
-- TABLA PERSONAL_ACCESS_TOKENS
-- ============================================

CREATE TABLE personal_access_tokens (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    tokenable_type VARCHAR(255) NOT NULL,
    tokenable_id BIGINT UNSIGNED NOT NULL,

    name VARCHAR(255) NOT NULL,
    token VARCHAR(64) NOT NULL UNIQUE,

    abilities TEXT NULL,
    last_used_at TIMESTAMP NULL,
    expires_at TIMESTAMP NULL,

    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    INDEX tokenable_index (tokenable_type, tokenable_id)
);

-- ============================================
-- TABLA PASSWORD_RESET_TOKENS
-- ============================================

CREATE TABLE password_reset_tokens (
    email VARCHAR(255) PRIMARY KEY,
    token VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
);

-- ============================================
-- TABLA FAILED_JOBS
-- ============================================

CREATE TABLE failed_jobs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    uuid VARCHAR(255) NOT NULL UNIQUE,
    connection TEXT NOT NULL,
    queue TEXT NOT NULL,
    payload LONGTEXT NOT NULL,
    exception LONGTEXT NOT NULL,
    failed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ============================================
-- INSERT ADMIN INICIAL
-- Password: Admin1234
-- ============================================

INSERT INTO users (nombre, apellido_paterno, apellido_materno, email, password, role, status)
VALUES (
    'Admin',
    'FilmHub',
    '',
    'admin@filmhub.com',
    '$2y$10$jCB5HY9fjPeAzKNiXuKra.tOeSjIkb4E2dV8CwCqLqy4DvDRl4cSy', -- Coloca el conamdo para volver a hashear esta contraseña en el código PHP
    'admin',
    'activo'
);
