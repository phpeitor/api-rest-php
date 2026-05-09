# Reglas de desarrollo — API REST PHP

Este documento recoge reglas mínimas que el equipo debe seguir al desarrollar y mantener este proyecto.

1. Separación de responsabilidades
- No mezclar HTML con CSS o JavaScript embebido en las vistas. Crear archivos separados en `assets/css/` y `assets/js/` y enlazarlos desde las vistas.
- No incluir scripts PHP de negocio dentro de HTML: la lógica debe residir en `includes/` o en controladores separados.

2. Configuración y secretos
- No subir el archivo `.env` al repositorio. Añadir valores de ejemplo en `.env.example`.
- Cargar variables de entorno con `vlucas/phpdotenv` y usar `$_ENV`/`getenv()` para acceder en el código.

3. Dependencias y composer
- Mantener `composer.json` y `composer.lock` en el repositorio.
- Ejecutar `composer audit` y `composer outdated` regularmente antes de actualizar paquetes.
- Cuando actualices una dependencia mayor, revisar breaking changes y ejecutar tests manuales.

4. Seguridad y buenas prácticas
- Usar sentencias preparadas (PDO con prepared statements) para todas las consultas que reciben datos de usuario.
- Validar y sanitizar entradas en el servidor. No confiar en validación del cliente.
- Nunca exponer claves privadas ni tokens en el repositorio.

5. Estilo de código
- Seguir PSR-12 para PHP y mantener indentación consistente.
- Escribir nombres de archivos y clases con convenciones claras. Prefiere `StudlyCase` para clases.

6. Estructura de assets y vistas
- Crear `assets/css/` y `assets/js/` y no insertar estilos en línea.
- Minimizar y versionar activos en producción (ej. `assets/css/app.css?v=1.0`).

7. Tests y QA
- Añadir pruebas unitarias básicas cuando se modifique lógica crítica (por ejemplo validaciones y CRUD).
- Ejecutar `php -l` en los archivos modificados antes de commitear.

8. Mensajes de commit y PRs
- Mensajes de commit claros en inglés o español: verbo en imperativo + breve descripción.
- Abrir PRs pequeños y describir el cambio, migraciones necesarias y cómo probar.

9. Migraciones
- Mantener el SQL en `db/` y proporcionar un script que automatice la ejecución (`db/migrate.php`).
- Documentar versiones de la BD si se realizan cambios estructurales.

10. Entorno local y despliegue
- Documentar en `README.md` los pasos para configurar localmente (PHP, Composer, MySQL) y ejecutar `db/migrate.php`.

Regla extra — No mezclar front/back en vistas:
- Si necesitas añadir un pequeño snippet de JS para interacción, colócalo al final del archivo en `assets/js/page-name.js` y enlázalo desde la vista.

Si quieres, puedo generar las carpetas `assets/css/` y `assets/js/` con archivos base y actualizar `index.php` para referenciarlos en lugar de usar estilos inline.