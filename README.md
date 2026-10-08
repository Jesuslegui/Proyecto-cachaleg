# Gestor de Inventario de Zapatería

Este proyecto es un sistema de gestión de inventario para una zapatería desarrollado en Laravel. Incluye gestión de productos, clientes, servicios de reparación y autenticación de usuarios. Diseñado para ser intuitivo para usuarios no técnicos.

## Características

- **Autenticación de usuarios**: Inicio de sesión seguro.
- **Gestión de inventario**: Agregar, editar, eliminar y ver productos (zapatos).
- **Gestión de clientes**: Mantener información de clientes.
- **Servicios**: Mostrar precios de servicios como cosida y pegada.
- **Interfaz intuitiva**: Botones grandes, iconos, texto claro, en español.
- **Dashboard**: Resumen de inventario, clientes, reparaciones y movimientos recientes.
- **Reparaciones**: Registro, búsqueda por cliente/teléfono, estados y asistente guiado.
- **Roles**: Administradores gestionan configuración; usuarios consultan inventario y atienden reparaciones.

## Requisitos del Sistema

- PHP 8.1 o superior
- Composer
- Base de datos (SQLite recomendado para simplicidad)

## Instalación

1. **Instalar PHP**:
   - Descarga desde https://windows.php.net/download/
   - Extrae a C:\php
   - Agrega C:\php a la variable PATH
   - Habilita extensiones: openssl, zip en php.ini

2. **Instalar Composer**:
   - Descarga el instalador desde https://getcomposer.org/
   - Ejecuta php composer-setup.php --install-dir=C:\php

3. **Crear el proyecto Laravel**:
   - Ejecuta `composer create-project laravel/laravel nombre-del-proyecto`
   - O clona este repositorio si existe.

4. **Instalar dependencias**:
   - `composer install`

5. **Configurar la base de datos**:
   - Edita .env: DB_CONNECTION=sqlite, DB_DATABASE=database/database.sqlite
   - Crea el archivo database/database.sqlite

6. **Ejecutar migraciones**:
   - `php artisan migrate`

7. **Publicar el almacenamiento de imágenes**:
   - `php artisan storage:link`

8. **Ejecutar seeders para datos iniciales**:
   - `php artisan db:seed` (agrega cosida, pegada y capellada con sus imágenes)

9. **Crear usuario administrador** (opcional):
   - Regístrate desde la aplicación o usa `php artisan tinker` para crear un usuario.

10. **Iniciar el servidor**:
   - `php artisan serve`
   - Accede a http://localhost:8000

## Estructura del Proyecto

- app/Models: Product, Customer, Service, User
- app/Http/Controllers: ProductController, CustomerController, ServiceController, Auth controllers
- resources/views: Vistas Blade con diseño intuitivo
- database/migrations: Migraciones para tablas
- database/seeders: Seeders para datos iniciales

## Uso

- Regístrate o inicia sesión.
- Navega por las secciones usando el menú.
- Agrega productos, clientes y servicios fácilmente.
- Los precios de servicios se muestran de forma destacada.

## Notas

- La interfaz usa Bootstrap con iconos para ser visual y fácil.
- Confirmaciones para eliminar elementos.
- Mensajes de éxito en español.

## Solución de Problemas

- Si hay errores de permisos, ejecuta como administrador.
- Verifica que las extensiones de PHP estén habilitadas.
- Asegúrate de que el archivo .env esté configurado correctamente.

## Mapeo y verificación

El análisis de la arquitectura, la conexión de base de datos y los cambios aplicados está en [docs/mapeo-proyecto.md](docs/mapeo-proyecto.md).

Comandos útiles desde la raíz:

```bash
php artisan migrate
php artisan view:cache
php artisan test
php artisan serve
```

La aplicación usa autenticación de sesión de Laravel porque su interfaz es Blade tradicional; JWT no aporta una ventaja para este flujo web.