CREATE DATABASE IF NOT EXISTS salones_min CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE salones_min;

-- ------------------------------------------------------------
-- Tabla: usuarios
-- ------------------------------------------------------------
CREATE TABLE usuarios (
    id_usuario BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    apellido_paterno VARCHAR(50) NOT NULL,
    apellido_materno VARCHAR(50) DEFAULT NULL,
    fecha_nacimiento DATE DEFAULT NULL,
    correo VARCHAR(100) NOT NULL UNIQUE,
    contrasena VARCHAR(255) NOT NULL,
    tipo_usuario ENUM('administrador','alumno') NOT NULL DEFAULT 'alumno',
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Tabla: salones
-- ------------------------------------------------------------
CREATE TABLE salones (
    id_salon BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE,
    ubicacion VARCHAR(100) DEFAULT NULL,
    capacidad TINYINT UNSIGNED DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Tabla: sensores
-- ------------------------------------------------------------
CREATE TABLE sensores (
    id_sensor BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    tipo VARCHAR(50) NOT NULL,
    id_salon BIGINT UNSIGNED NOT NULL,
    FOREIGN KEY (id_salon) REFERENCES salones(id_salon) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Tabla: lecturas
-- ------------------------------------------------------------
CREATE TABLE lecturas (
    id_lectura BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_sensor BIGINT UNSIGNED NOT NULL,
    valor DECIMAL(6,2) NOT NULL,
    fecha_hora TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_sensor) REFERENCES sensores(id_sensor) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Tabla: reportes
-- ------------------------------------------------------------
CREATE TABLE reportes (
    id_reporte BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_salon BIGINT UNSIGNED NULL,
    tipo ENUM('diario','semanal','mensual') NOT NULL,
    valor_max DECIMAL(6,2) DEFAULT NULL,
    valor_min DECIMAL(6,2) DEFAULT NULL,
    valor_promedio DECIMAL(6,2) DEFAULT NULL,
    fecha_inicio DATE NOT NULL,
    fecha_fin DATE NOT NULL,
    FOREIGN KEY (id_salon) REFERENCES salones(id_salon) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Tabla: alumno_salon
-- ------------------------------------------------------------
CREATE TABLE alumno_salon (
    id_relacion BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_usuario BIGINT UNSIGNED NOT NULL,
    id_salon BIGINT UNSIGNED NOT NULL,
    fecha_asignacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario) ON DELETE CASCADE,
    FOREIGN KEY (id_salon) REFERENCES salones(id_salon) ON DELETE CASCADE,
    UNIQUE KEY usuario_salon_unique (id_usuario, id_salon)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Datos de ejemplo
-- ------------------------------------------------------------

-- Usuarios
INSERT INTO usuarios (nombre, apellido_paterno, apellido_materno, fecha_nacimiento, correo, contrasena, tipo_usuario)
VALUES 
('Ricardo', 'Gregorio', 'Lopez', '2002-05-12', 'rgregorio0@ucol.mx', 'paco?', 'alumno'),
('Maria', 'Perez', NULL, '2003-08-20', 'mperez@ucol.mx', '123456', 'alumno'),
('Luis', 'Hernandez', 'Gomez', '2001-11-05', 'lhernandez@ucol.mx', 'abc123', 'alumno'),
('Admin', 'Sistema', NULL, '1990-01-01', 'admin@ucol.mx', 'adminpass', 'administrador');

-- Salones
INSERT INTO salones (nombre, ubicacion, capacidad) VALUES
('5D', 'Ruinas del antiguo laboratorio de redes Cisco', 15),
('6A', 'Laboratorio de programación', 20);

-- Sensores
INSERT INTO sensores (tipo, id_salon) VALUES
('DHT11', 1),
('DHT22', 2);

-- Lecturas
INSERT INTO lecturas (id_sensor, valor, fecha_hora) VALUES
(1, 25.5, '2025-10-05 09:00:00'),
(1, 26.0, '2025-10-05 12:00:00'),
(2, 22.3, '2025-10-05 09:00:00');

-- Alumno en salones
INSERT INTO alumno_salon (id_usuario, id_salon) VALUES
(1,1),
(2,1),
(3,2);

-- Reporte ejemplo
INSERT INTO reportes (id_salon, tipo, valor_max, valor_min, valor_promedio, fecha_inicio, fecha_fin)
VALUES
(1, 'diario', 26.0, 25.5, 25.75, '2025-10-05', '2025-10-05'),
(2, 'diario', 22.3, 22.3, 22.3, '2025-10-05', '2025-10-05');