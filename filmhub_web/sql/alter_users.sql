-- ============================================
-- SCRIPT PARA MIGRAR BD EXISTENTE
-- Solo ejecutar si ya tienes la BD original con el campo 'name'
-- Si usas filmhub_api_db.sql desde cero, NO necesitas este script
-- ============================================

USE filmhub_api_db;

-- Renombrar 'name' a 'nombre'
ALTER TABLE users CHANGE name nombre VARCHAR(150) NOT NULL;

-- Agregar campos de apellido
ALTER TABLE users
  ADD COLUMN apellido_paterno VARCHAR(100) NULL AFTER nombre,
  ADD COLUMN apellido_materno VARCHAR(100) NULL AFTER apellido_paterno;

-- Actualizar el admin existente
UPDATE users
SET nombre = 'Admin',
    apellido_paterno = 'FilmHub',
    apellido_materno = ''
WHERE email = 'admin@filmhub.com';
