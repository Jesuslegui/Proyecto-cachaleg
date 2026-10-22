# Gestor de Inventario de Zapatería

Este proyecto es un sistema de gestión de inventario para una zapatería desarrollado en Laravel. Incluye gestión de productos, clientes, servicios de reparación y autenticación de usuarios. Diseñado para ser intuitivo para usuarios no técnicos.

## Características

- **Autenticación de usuarios**: Inicio de sesión seguro.
- **Verificación en dos pasos opcional**: Cada usuario puede activarla desde Seguridad y confirmar el inicio por correo.
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

## Verificación en dos pasos y Gmail

La verificación en dos pasos viene desactivada para todas las cuentas existentes. Después de ejecutar `php artisan migrate`, cada usuario autenticado puede activarla o desactivarla desde **Seguridad**; se solicita la contraseña actual para confirmar el cambio. Al iniciar sesión con la función activada, se envía un código numérico de 5 dígitos, almacenado como hash, válido durante 5 minutos y con un máximo de 5 intentos de verificación. Si vence o se agotan los intentos, se puede solicitar un código nuevo. Se permiten hasta 3 envíos cada 10 minutos por cuenta y dirección IP.

Para enviar correo con Gmail, habilita la verificación en dos pasos de tu cuenta de Google y crea una **contraseña de aplicación** en la configuración de seguridad de Google. Usa esa contraseña de aplicación (no la contraseña normal de Gmail) como `MAIL_PASSWORD`. La opción sólo aparece si la cuenta de Google cumple los requisitos de Google y puede no estar disponible en cuentas administradas.

Configura estas variables en `.env` sin agregar credenciales al repositorio:

```dotenv
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=tu-cuenta@gmail.com
MAIL_PASSWORD=tu-contraseña-de-aplicación
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=tu-cuenta@gmail.com
MAIL_FROM_NAME="${APP_NAME}"
TWO_FACTOR_LOGIN_MAX_EMAILS=3
TWO_FACTOR_LOGIN_RATE_LIMIT_SECONDS=600
TWO_FACTOR_LOGIN_MAX_ATTEMPTS=5
```

En el puerto 587, `MAIL_ENCRYPTION=tls` usa STARTTLS. Tras cambiar variables, ejecuta `php artisan config:clear` (o reconstruye la caché de configuración del despliegue).

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