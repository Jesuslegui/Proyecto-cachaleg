# Mapeo del proyecto Cachaleg

## Proyecto activo

La aplicación que ejecutan `artisan`, `composer.json` y `public/index.php` está en la raíz. `laravel-12.x/` y `zapateria/` son proyectos Laravel separados y no participan en el autoload de la aplicación raíz.

## Funcionamiento actual

- **Entrada web:** `routes/web.php` carga autenticación por sesión y recursos de inventario, clientes, reparaciones, proveedores y servicios.
- **Autenticación:** `Auth::attempt()` regenera la sesión y las contraseñas se hashean mediante el cast de `User`. No se añadió JWT porque la aplicación usa Blade y sesiones.
- **Inventario:** `ProductController` gestiona productos y `Product` usa borrado lógico. La consulta acepta búsqueda y disponibilidad; los cambios de stock generan `InventoryMovement`.
- **Clientes:** `CustomerController` y sus vistas CRUD conservan el registro y consulta.
- **Reparaciones:** `RepairController` relaciona cliente, servicio y usuario. Sus vistas incluyen filtros por nombre/teléfono y estados controlados.
- **Asistente:** `RepairAssistantController` usa sesión y reglas locales, sin API externa. Valida teléfono, consulta estados y confirma antes de crear una reparación.
- **Roles:** `EnsureUserHasRole` protege la administración. `admin` gestiona inventario, proveedores, servicios y usuarios; `user` consulta inventario y trabaja con clientes, reparaciones y asistente.

## Base de datos comprobada

La configuración activa usa MySQL con la base `zapateria_db`; el SQLite presente en `database/database.sqlite` estaba vacío y no era la conexión activa. Las migraciones nuevas añadieron `repairs`, `inventory_movements`, `users.role` y `deleted_at` en entidades relevantes. No se eliminaron tablas ni registros.

## Cambios realizados

- Dashboard protegido con métricas de inventario, clientes, reparaciones y últimos movimientos.
- Navegación responsive con acceso a reparaciones y administración visible sólo para administradores.
- Búsqueda y filtros de inventario y reparaciones.
- Registro transaccional de entradas y salidas, rechazando salidas mayores al stock.
- Vistas Blade de reparaciones y asistente conversacional.
- Gestión de roles y desactivación lógica de usuarios, sin permitir que un administrador se quite su propio acceso.
- Validación realizada con `php artisan test` y `php artisan view:cache`.

## Pendientes recomendados

- Añadir pruebas de integración para cada rol y el flujo completo del asistente.
- Crear un Form Request por recurso cuando se amplíen las validaciones.
- Configurar un administrador mediante un procedimiento seguro fuera del repositorio; no se sembró una contraseña fija.
- Sustituir CDN por assets versionados si el despliegue necesita funcionar sin internet.