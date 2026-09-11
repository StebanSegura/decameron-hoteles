-- =====================================================================
-- Datos catálogo (no requieren administración/CRUD, según requerimiento)
-- =====================================================================

INSERT INTO ciudades (nombre) VALUES
    ('CARTAGENA'), ('SAN ANDRÉS'), ('SANTA MARTA'), ('BARRANQUILLA'),
    ('MEDELLÍN'), ('BOGOTÁ'), ('CALI'), ('TOLÚ'), ('GIRARDOT'), ('PEREIRA')
ON CONFLICT (nombre) DO NOTHING;

INSERT INTO tipos_habitacion (nombre) VALUES
    ('ESTANDAR'), ('JUNIOR'), ('SUITE')
ON CONFLICT (nombre) DO NOTHING;

INSERT INTO acomodaciones (nombre) VALUES
    ('SENCILLA'), ('DOBLE'), ('TRIPLE'), ('CUADRUPLE')
ON CONFLICT (nombre) DO NOTHING;

-- Reglas de negocio: tipo de habitación -> acomodaciones permitidas
-- ESTANDAR: Sencilla, Doble
INSERT INTO tipo_habitacion_acomodacion (tipo_habitacion_id, acomodacion_id)
SELECT th.id, a.id FROM tipos_habitacion th, acomodaciones a
WHERE th.nombre = 'ESTANDAR' AND a.nombre IN ('SENCILLA', 'DOBLE')
ON CONFLICT DO NOTHING;

-- JUNIOR: Triple, Cuádruple
INSERT INTO tipo_habitacion_acomodacion (tipo_habitacion_id, acomodacion_id)
SELECT th.id, a.id FROM tipos_habitacion th, acomodaciones a
WHERE th.nombre = 'JUNIOR' AND a.nombre IN ('TRIPLE', 'CUADRUPLE')
ON CONFLICT DO NOTHING;

-- SUITE: Sencilla, Doble, Triple
INSERT INTO tipo_habitacion_acomodacion (tipo_habitacion_id, acomodacion_id)
SELECT th.id, a.id FROM tipos_habitacion th, acomodaciones a
WHERE th.nombre = 'SUITE' AND a.nombre IN ('SENCILLA', 'DOBLE', 'TRIPLE')
ON CONFLICT DO NOTHING;

-- Hotel de ejemplo tomado del enunciado de la prueba
INSERT INTO hoteles (nombre, direccion, ciudad_id, nit, numero_habitaciones)
SELECT 'DECAMERON CARTAGENA', 'CALLE 23 58-25', c.id, '12345678-9', 42
FROM ciudades c WHERE c.nombre = 'CARTAGENA'
ON CONFLICT (nit) DO NOTHING;

INSERT INTO hotel_configuraciones (hotel_id, tipo_habitacion_id, acomodacion_id, cantidad)
SELECT h.id, th.id, a.id, 25
FROM hoteles h, tipos_habitacion th, acomodaciones a
WHERE h.nit = '12345678-9' AND th.nombre = 'ESTANDAR' AND a.nombre = 'SENCILLA'
ON CONFLICT DO NOTHING;

INSERT INTO hotel_configuraciones (hotel_id, tipo_habitacion_id, acomodacion_id, cantidad)
SELECT h.id, th.id, a.id, 12
FROM hoteles h, tipos_habitacion th, acomodaciones a
WHERE h.nit = '12345678-9' AND th.nombre = 'JUNIOR' AND a.nombre = 'TRIPLE'
ON CONFLICT DO NOTHING;

INSERT INTO hotel_configuraciones (hotel_id, tipo_habitacion_id, acomodacion_id, cantidad)
SELECT h.id, th.id, a.id, 5
FROM hoteles h, tipos_habitacion th, acomodaciones a
WHERE h.nit = '12345678-9' AND th.nombre = 'ESTANDAR' AND a.nombre = 'DOBLE'
ON CONFLICT DO NOTHING;
