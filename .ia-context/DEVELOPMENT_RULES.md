# Reglas de desarrollo — API REST en PHP nativo

## Contexto y alcance

Este repositorio implementa una API REST para operaciones CRUD de clientes/usuarios. Está construida con PHP 8.3+, sin framework PHP, usa PDO y MySQL/MariaDB, JWT para autenticación y Composer para dependencias. Apache sirve los endpoints PHP; `index.html` y `assets/` contienen una interfaz sencilla para probarlos.

Antes de cambiar código, inspeccionar la implementación real y respetar sus contratos existentes. No asumir rutas, campos, métodos HTTP, formato de respuesta ni dependencias que no estén documentados o implementados. No convertir el proyecto en otro producto ni añadir arquitectura o dependencias sin una necesidad concreta.

## Estructura del proyecto

- `api/`: endpoints HTTP; mantenerlos pequeños y delegar acceso a datos/lógica en `includes/`.
- `includes/`: clases y lógica compartida, actualmente acceso a base de datos y clientes.
- `db/`: SQL y scripts de migración.
- `bin/`: herramientas CLI, como generación de tokens.
- `assets/css/`, `assets/js/`: presentación e interacción de la interfaz de prueba.
- `index.html`: interfaz web de prueba de la API.
- `token.php`: compatibilidad/integración existente relacionada con autenticación; revisar antes de modificar.
- `.ia-context/`: instrucciones de desarrollo asistido por IA.

## Reglas obligatorias

1. **PHP nativo:** no introducir frameworks ni capas arquitectónicas grandes. Mantener responsabilidades claras entre transporte HTTP (`api/`), lógica/datos (`includes/`) y presentación.
2. **PHP y estilo:** requerir PHP 8.3+, seguir PSR-12, usar nombres claros y ejecutar `php -l` en cada PHP modificado.
3. **HTTP/REST:** respetar los métodos y rutas existentes. Validar el método, entrada y parámetros; devolver códigos HTTP apropiados y respuestas JSON consistentes con el contrato del endpoint. Definir `Content-Type: application/json; charset=utf-8` en respuestas JSON.
4. **Base de datos:** usar PDO y consultas preparadas para todo dato externo. No concatenar entrada del usuario en SQL. Gestionar errores sin filtrar detalles internos al cliente.
5. **Validación:** validar en el servidor tipos, formato, longitud y campos requeridos. La validación del frontend es solo una ayuda de uso.
6. **Autenticación y secretos:** conservar el esquema JWT existente y verificar tokens de manera segura. No incluir tokens, claves, credenciales ni `.env` en el repositorio o las respuestas. Usar `.env.example` para documentar variables ficticias y cargar configuración mediante `vlucas/phpdotenv` donde corresponda.
7. **Seguridad:** no devolver contraseñas ni hashes; usar `password_hash()`/`password_verify()` si se gestionan contraseñas. Escapar datos al insertarlos en HTML y no exponer trazas, rutas internas o credenciales.
8. **Dependencias:** mantener `composer.json` y `composer.lock` sincronizados. Añadir o actualizar paquetes solo si es necesario; revisar compatibilidad y ejecutar `composer audit` cuando se cambien dependencias.
9. **Migraciones:** mantener scripts y SQL en `db/`; documentar cambios de esquema y conservar compatibilidad con el mecanismo de migración existente.
10. **Frontend de prueba:** mantener HTML, CSS y JavaScript separados; usar archivos de `assets/`, no incorporar lógica de negocio PHP en la interfaz ni estilos/scripts inline nuevos.
11. **Documentación y pruebas:** actualizar `README.md` ante cambios de instalación, configuración, endpoints, contratos o base de datos. Probar los flujos afectados y añadir pruebas para cambios críticos cuando el proyecto disponga de infraestructura adecuada.

## Flujo de trabajo para asistentes de IA

1. Leer el endpoint, clases relacionadas, esquema SQL y documentación antes de editar.
2. Proponer el cambio mínimo que cumpla el objetivo y preserve compatibilidad.
3. No inventar requisitos ni reescribir archivos completos si basta un cambio localizado.
4. Comprobar entradas inválidas, errores de base de datos y respuestas HTTP/JSON en cambios de API.
5. Verificar sintaxis PHP con `php -l` y ejecutar las comprobaciones pertinentes disponibles.
6. Resumir archivos modificados, comportamiento y verificaciones realizadas; señalar claramente cualquier limitación de pruebas.

## Entorno documentado

- PHP 8.3 o superior con PDO y el controlador de MySQL habilitados.
- MySQL 5.7 o MariaDB 10.0 o superior, según `README.md`.
- Composer; Apache en el entorno local descrito por el README.
- Migraciones mediante `php db/migrate.php` después de configurar el entorno.
