## API Rest PHP 🐘
[![forthebadge](http://forthebadge.com/images/badges/for-robots.svg)](https://www.linkedin.com/in/drphp/)
[![forthebadge](http://forthebadge.com/images/badges/built-with-love.svg)](https://www.linkedin.com/in/drphp/)

[![Video](https://img.youtube.com/vi/p-I0_x5ApjA/0.jpg)](https://www.youtube.com/watch?v=p-I0_x5ApjA)  

[![Video Demo](https://img.shields.io/badge/YouTube-FF0000?style=for-the-badge&logo=youtube)](https://www.youtube.com/watch?v=p-I0_x5ApjA)

## Endpoints

| Método | Endpoint | Descripción |
|--------|----------|-------------|
| **GET** | `http://localhost/api-rest-php/api/get_all_client.php` | Listar todos los usuarios |
| **GET** | `http://localhost/api-rest-php/api/get_client_id.php/{id}` | Obtener usuario por ID |
| **POST** | `http://localhost/api-rest-php/api/create_client.php` | Crear nuevo usuario |
| **PATCH** | `http://localhost/api-rest-php/api/update_client.php` | Actualizar usuario |
| **DELETE** | `http://localhost/api-rest-php/api/delete_client.php` | Eliminar usuario |

### Ejemplo de uso

**GET — Listar usuarios:**
```bash
curl -H "Authorization: Bearer <token>" \
  "http://localhost/api-rest-php/api/get_all_client.php"
```

**GET — Obtener usuario por ID:**
```bash
curl -H "Authorization: Bearer <token>" \
  "http://localhost/api-rest-php/api/get_client_id.php/00001"
```

**POST — Crear usuario:**
```bash
curl -X POST \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer <token>" \
  -d '{"id":"00011","paterno":"Smith","materno":"Doe","nombres":"John","correo":"john@example.com","clave":"pass123","semilla":"seed"}' \
  "http://localhost/api-rest-php/api/create_client.php"
```

**PATCH — Actualizar usuario:**
```bash
curl -X PATCH \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer <token>" \
  -d '{"id":"00001","paterno":"New","materno":"Name","nombres":"Updated"}' \
  "http://localhost/api-rest-php/api/update_client.php"
```

**DELETE — Eliminar usuario:**
```bash
curl -X DELETE \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer <token>" \
  -d '{"id":"00001"}' \
  "http://localhost/api-rest-php/api/delete_client.php"
```

### Interfaz web
Accede a la interfaz interactiva en:
```
http://localhost/api-rest-php/
```
La documentación puede leerse en formato HTML en `http://localhost/api-rest-php/docs.php`; se renderiza de forma segura desde este README.

## Requerimientos
- PHP 8.3 o superior con controladores PDO habilitados
- MySQL 5.7 / MariaDB 10.0

## Estructura del proyecto

```text
api/                    Endpoints HTTP (se conservan las URLs existentes)
api/dev/token.php       Token de conveniencia solo para entorno local
src/Client/             Lógica de clientes
src/Database/           Conexión PDO y configuración de datos
database/migrations/    Migraciones SQL versionadas
database/migrate.php    Ejecutor de migraciones
bin/                    Herramientas de línea de comandos
assets/                 CSS y JavaScript de la interfaz de prueba
docs.php                Visor HTML seguro para README.md
includes/               Cargadores de compatibilidad para rutas antiguas
db/migrate.php          Alias compatible para la migración
```

## Migracion completada (PHP 8.3 + Composer)

Estado actual del proyecto:
- Composer inicializado en la raiz del repo
- Archivo `composer.json` agregado
- Archivo `composer.lock` generado
- Dependencia JWT actualizada a `firebase/php-jwt` v7.0.5
- Dependencia de entorno agregada: `vlucas/phpdotenv` (carga automatica de `.env`)
- Renderizado de Markdown con `league/commonmark` (README en formato HTML)
- Auditoria de Composer sin vulnerabilidades conocidas (`composer audit`)

### Variables de entorno (.env)
La conexion a base de datos ya no usa valores fijos en codigo.
Ahora se cargan automaticamente desde el archivo `.env`.

Archivos:
- `.env` (local, no versionado)
- `.env.example` (plantilla para el equipo)

Variables usadas:
- `DB_HOST`
- `DB_USER`
- `DB_PASSWORD`
- `DB_NAME`
- `DB_CHARSET`

Dependencia Composer usada para la carga automatica:
```bash
composer require vlucas/phpdotenv:^5.6
```

### Actualizar Composer (global)
```bash
composer self-update
composer --version
```

### Instalar dependencias
```bash
cd c:/Apache24/htdocs/api-rest-php
composer install
```

### Actualizar dependencias
```bash
composer update
```

### Verificar estado de dependencias
```bash
composer show
composer outdated
composer audit
```

### Verificacion recomendada de sintaxis
```bash
Get-ChildItem -Path . -Recurse -Filter *.php | ForEach-Object { php -l $_.FullName }
```

### Ejecutar migración
La migración inicial está en `database/migrations/001_initial.sql` y crea la base `bd_test`, la tabla `usuario` y datos de prueba. El SQL elimina y recrea `usuario`; úsalo solo en una base de desarrollo.

Ejecutar por CLI:
```bash
composer db:migrate
```

También se conserva `php db/migrate.php`. Si no usas Composer, ejecuta `php database/migrate.php`.

Con credenciales personalizadas:
```bash
php database/migrate.php --host=localhost --user=root --password=
```

Tambien se puede ejecutar desde navegador:
```text
http://localhost/api-rest-php/database/migrate.php
```

Resultado esperado:
- Crea la base de datos `bd_test`
- Crea la tabla `usuario`
- Carga usuarios de prueba

### Generar nuevos tokens JWT
El proyecto usa `firebase/php-jwt` para validar y generar tokens.

Token actual (en `.env`):
- Variable: `API_TOKEN`
- Ubicación: `.env` (no versionado)

Generar un token nuevo:
```bash
php bin/generate-token.php
```

Con nombre y compañía personalizados:
```bash
php bin/generate-token.php --name="John Doe" --company="My Company"
```

Con expiración personalizada (en segundos):
```bash
php bin/generate-token.php --exp=3600
```

Una vez generado el token:
1. Cópialo desde la salida.
2. Actualiza `API_TOKEN` en el archivo `.env`.
3. El token se auto-cargará en la interfaz web en el campo Token.
4. Úsalo en requests con: `Authorization: Bearer <token>`
