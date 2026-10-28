# Transporte

Sistema de gestión de transporte construido con Laravel. Este repositorio contiene el código fuente y las migraciones; no incluye la base de datos local, archivos `.env`, documentos cargados ni sesiones de usuarios.

## Módulos

- Empresas, operadores, tractos y equipos
- Asignaciones de operador y unidades
- Viajes y sus gastos de combustible, casetas y reparaciones
- Mantenimientos y piezas
- Documentos de operadores, tractos y equipos
- Guías y alertas de documentos o mantenimientos
- Usuarios, roles y permisos por módulo
- Reportes y calificaciones de operadores

## Requisitos

PHP 8.2 o compatible con `composer.json`, Composer, Node.js/npm y MySQL o MariaDB.

## Instalación local

1. Instala las dependencias PHP: `composer install`.
2. Copia `.env.example` a `.env` y configura la conexión de base de datos.
3. Ejecuta `php artisan key:generate`.
4. Crea la base de datos configurada en `.env` y corre `php artisan migrate --seed`.
5. Instala dependencias frontend con `npm install` y compila con `npm run build`.
6. Inicia Laravel con `php artisan serve`.

El seeder actual no carga registros de demostración. Para compartir una base de ejemplo, agrega datos ficticios mediante seeders antes de generar un volcado; evita subir datos personales o información de producción.
