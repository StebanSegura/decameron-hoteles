-- =====================================================================
-- HOTELES DECAMERON DE COLOMBIA - Esquema de Base de Datos (PostgreSQL)
-- =====================================================================
-- Convenciones: snake_case, llaves foráneas con ON DELETE RESTRICT/CASCADE
-- según corresponda, restricciones UNIQUE para reglas de negocio.
-- =====================================================================

CREATE TABLE IF NOT EXISTS ciudades (
    id          SERIAL PRIMARY KEY,
    nombre      VARCHAR(120) NOT NULL UNIQUE
);

CREATE TABLE IF NOT EXISTS tipos_habitacion (
    id          SERIAL PRIMARY KEY,
    nombre      VARCHAR(60) NOT NULL UNIQUE
);

CREATE TABLE IF NOT EXISTS acomodaciones (
    id          SERIAL PRIMARY KEY,
    nombre      VARCHAR(60) NOT NULL UNIQUE
);

-- Catálogo que define qué acomodaciones son válidas para cada tipo de
-- habitación (regla de negocio persistida, además de validarse en el back).
CREATE TABLE IF NOT EXISTS tipo_habitacion_acomodacion (
    id                  SERIAL PRIMARY KEY,
    tipo_habitacion_id  INTEGER NOT NULL REFERENCES tipos_habitacion(id) ON DELETE CASCADE,
    acomodacion_id      INTEGER NOT NULL REFERENCES acomodaciones(id) ON DELETE CASCADE,
    UNIQUE (tipo_habitacion_id, acomodacion_id)
);

CREATE TABLE IF NOT EXISTS hoteles (
    id                  SERIAL PRIMARY KEY,
    nombre              VARCHAR(150) NOT NULL,
    direccion           VARCHAR(200) NOT NULL,
    ciudad_id           INTEGER NOT NULL REFERENCES ciudades(id) ON DELETE RESTRICT,
    nit                 VARCHAR(30) NOT NULL UNIQUE,
    numero_habitaciones INTEGER NOT NULL CHECK (numero_habitaciones > 0),
    created_at          TIMESTAMP NOT NULL DEFAULT NOW(),
    updated_at          TIMESTAMP NOT NULL DEFAULT NOW()
);

-- Evita hoteles repetidos también por nombre dentro de la misma ciudad.
CREATE UNIQUE INDEX IF NOT EXISTS ux_hoteles_nombre_ciudad
    ON hoteles (LOWER(nombre), ciudad_id);

CREATE TABLE IF NOT EXISTS hotel_configuraciones (
    id                  SERIAL PRIMARY KEY,
    hotel_id            INTEGER NOT NULL REFERENCES hoteles(id) ON DELETE CASCADE,
    tipo_habitacion_id  INTEGER NOT NULL REFERENCES tipos_habitacion(id) ON DELETE RESTRICT,
    acomodacion_id      INTEGER NOT NULL REFERENCES acomodaciones(id) ON DELETE RESTRICT,
    cantidad            INTEGER NOT NULL CHECK (cantidad > 0),
    created_at          TIMESTAMP NOT NULL DEFAULT NOW(),
    updated_at          TIMESTAMP NOT NULL DEFAULT NOW(),
    -- Regla: no puede repetirse el mismo tipo+acomodación para el mismo hotel
    UNIQUE (hotel_id, tipo_habitacion_id, acomodacion_id)
);

CREATE INDEX IF NOT EXISTS ix_hotel_configuraciones_hotel ON hotel_configuraciones(hotel_id);

-- Trigger para mantener updated_at
CREATE OR REPLACE FUNCTION fn_set_updated_at() RETURNS TRIGGER AS $$
BEGIN
    NEW.updated_at = NOW();
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS trg_hoteles_updated_at ON hoteles;
CREATE TRIGGER trg_hoteles_updated_at
    BEFORE UPDATE ON hoteles
    FOR EACH ROW EXECUTE FUNCTION fn_set_updated_at();

DROP TRIGGER IF EXISTS trg_hotel_config_updated_at ON hotel_configuraciones;
CREATE TRIGGER trg_hotel_config_updated_at
    BEFORE UPDATE ON hotel_configuraciones
    FOR EACH ROW EXECUTE FUNCTION fn_set_updated_at();
