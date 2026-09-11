# Hoteles Decameron de Colombia — Gestión de Hoteles y Habitaciones

Prueba técnica: sistema para registrar los hoteles de la compañía (con
sus datos tributarios) y configurar, por hotel, los tipos de habitación
(Estándar, Junior, Suite) con su acomodación correspondiente, validando
las reglas de negocio del área de operaciones hoteleras.

## Stack técnico

| Capa | Tecnología |
|---|---|
| Backend | **Laravel 11** (PHP 8.2+), API RESTful |
| Frontend | **React 18** + Vite + React Router + React-Bootstrap |
| Base de datos | **PostgreSQL** (SQLite en memoria para pruebas automatizadas) |
| Pruebas backend | **PHPUnit** (unitarias + de integración) |
| Pruebas frontend | **Vitest** + Testing Library |

Backend y frontend están completamente **desacoplados**: se comunican
únicamente vía HTTP/JSON (REST). El frontend puede desplegarse en un
dominio distinto al del backend gracias a CORS.


## Reglas de negocio implementadas

1. **Acomodación válida según tipo de habitación**
   - ESTÁNDAR → Sencilla o Doble
   - JUNIOR → Triple o Cuádruple
   - SUITE → Sencilla, Doble o Triple

   Implementado con patrón **Strategy** (`App\Services\Validation\*AcomodacionRule`)
   + **Factory Method** (`AcomodacionRuleFactory`), reforzado con el
   catálogo `tipo_habitacion_acomodacion` en base de datos.

2. **La cantidad de habitaciones configuradas no debe superar el máximo
   del hotel** — `ConfiguracionService::validarCapacidadMaxima()`.

3. **No deben existir hoteles repetidos** — por NIT (único) y por
   nombre+ciudad, con restricciones `UNIQUE` a nivel de migración.

4. **No debe repetirse tipo de habitación + acomodación para el mismo
   hotel** — `UNIQUE(hotel_id, tipo_habitacion_id, acomodacion_id)`.

5. **Los catálogos (ciudades, tipos de habitación, acomodaciones) no
   requieren administración** — solo hay endpoints `GET` de lectura.
