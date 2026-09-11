# Guía paso a paso — Ejecutar el proyecto en local (Laravel + React, Laragon/Windows)

Esta guía asume Laragon + PostgreSQL 16 en Windows (como nos comentaste).
A diferencia de la versión anterior del proyecto, **esta sí necesita
internet** en dos momentos puntuales: para instalar Laravel (Composer)
y para instalar las dependencias de React (npm). Son comandos oficiales,
no descargas manuales.

---

## Paso 0 — Verifica que tienes Composer y Node en Laragon

1. Abre Laragon → menú **Herramientas (⋮)** → **Quick app** → confirma
   que **Composer** aparece instalable/instalado.
2. En el mismo menú, confirma que tienes **Node.js** (Laragon lo ofrece
   como Quick app también). Si no lo tienes, instálalo desde ahí.
3. Abre **Laragon → Terminal** y verifica versiones:
   ```
   php -v
   composer -V
   node -v
   npm -v
   ```

---

## Paso 1 — Crear el proyecto Laravel real

El backend que te entregamos NO incluye el framework Laravel en sí (los
paquetes `illuminate/*` se descargan de Packagist), solo el código de
la aplicación (Models, Controllers, Services, migraciones, tests). Vas
a generar el esqueleto real de Laravel y luego copiar nuestros archivos
encima.

1. En la terminal de Laragon:
   ```
   cd C:\laragon\www
   composer create-project laravel/laravel decameron-backend "^11.0"
   ```
   Esto descarga Laravel y todas sus dependencias (necesita internet).

2. Del proyecto que te entregamos, copia el contenido de nuestra
   carpeta `backend/` DENTRO de `C:\laragon\www\decameron-backend`,
   **sobrescribiendo** cuando te lo pida (son las carpetas `app/`,
   `database/`, `routes/`, `tests/`, `config/cors.php`, `phpunit.xml`,
   `.env.example`).

   Es decir, al final `C:\laragon\www\decameron-backend\app\Models\Hotel.php`
   debe existir (viene de nuestro paquete), conviviendo con los archivos
   propios de Laravel (`artisan`, `bootstrap/`, `vendor/`, etc.) que
   generó el `composer create-project`.

3. Registra nuestro `RepositoryServiceProvider`:
   - Abre `bootstrap/providers.php` (Laravel 11) y agrega una línea:
     ```php
     App\Providers\RepositoryServiceProvider::class,
     ```
     dentro del arreglo que ya existe ahí.

---

## Paso 2 — Configurar el backend

1. Dentro de `C:\laragon\www\decameron-backend`, copia `.env.example`
   como `.env` (reemplaza el que trae Laravel por defecto), y ajusta
   la contraseña de PostgreSQL:
   ```
   DB_CONNECTION=pgsql
   DB_HOST=127.0.0.1
   DB_PORT=5432
   DB_DATABASE=decameron_hoteles
   DB_USERNAME=postgres
   DB_PASSWORD=TU_CONTRASEÑA
   CORS_ALLOWED_ORIGINS=http://localhost:5173
   ```
2. Genera la clave de la aplicación:
   ```
   php artisan key:generate
   ```
3. Habilita `pdo_pgsql` en PHP si no lo has hecho (click derecho en
   Laragon → PHP → php.ini → quita el `;` de `extension=pdo_pgsql` y
   `extension=pgsql` → guarda → reinicia Laragon).
4. Crea la base de datos `decameron_hoteles` en pgAdmin (click derecho
   en Databases → Create → Database... → nombre `decameron_hoteles`).
5. Corre las migraciones y los seeders (crea las tablas y carga los
   catálogos + el hotel de ejemplo):
   ```
   php artisan migrate --seed
   ```
6. Corre las pruebas para confirmar que todo quedó bien instalado
   (usan SQLite en memoria, no tocan tu base de datos real):
   ```
   php artisan test
   ```
7. Levanta el servidor:
   ```
   php artisan serve
   ```
   Verifica en el navegador: `http://localhost:8000/api/health`

---

## Paso 3 — Crear el proyecto React real

1. En otra terminal de Laragon:
   ```
   cd C:\laragon\www
   npm create vite@latest decameron-frontend -- --template react
   ```
2. Copia el contenido de nuestra carpeta `frontend/` DENTRO de
   `C:\laragon\www\decameron-frontend`, sobrescribiendo `src/`,
   `index.html`, `vite.config.js` y `package.json`.
3. Instala las dependencias (React-Bootstrap, React Router, Vitest, etc.
   ya están declaradas en el `package.json` que te entregamos):
   ```
   cd C:\laragon\www\decameron-frontend
   npm install
   ```
4. Copia `.env.example` como `.env` y confirma que apunta a tu backend:
   ```
   VITE_API_URL=http://localhost:8000/api
   ```
5. Corre las pruebas del frontend:
   ```
   npm test
   ```
6. Levanta el servidor de desarrollo:
   ```
   npm run dev
   ```
   Abre `http://localhost:5173` en Chrome o Firefox.

¡Listo! Deberías ver "Hoteles registrados" con **DECAMERON CARTAGENA**
ya cargado desde el seeder.

---

## Resumen para el día a día

1. Servicio de PostgreSQL iniciado (revisa en `services.msc`).
2. Terminal 1: `cd decameron-backend && php artisan serve`
3. Terminal 2: `cd decameron-frontend && npm run dev`
4. Abrir `http://localhost:5173`

---