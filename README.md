# API Rest PHP 💻🐘

    GET /api-rest-php/api/get_all_client.php → Listar
---
    POST /api-rest-php/api/create_client.php → Registrar
---
    POST /api-rest-php/api/update_client.php → Actualizar
---
    POST /api-rest-php/api/delete_client.php → Eliminar
---
    GET /api-rest-php/api/get_client_id.php → Obtener por id

# Requerimientos
- PHP 8.3 o superior con controladores PDO habilitados
- MySQL 5.7 / MariaDB 10.0

# Migracion completada (PHP 8.3 + Composer)

Estado actual del proyecto:
- Composer inicializado en la raiz del repo
- Archivo `composer.json` agregado
- Archivo `composer.lock` generado
- Dependencia JWT actualizada a `firebase/php-jwt` v7.0.5
- Dependencia de entorno agregada: `vlucas/phpdotenv` (carga automatica de `.env`)
- Auditoria de Composer sin vulnerabilidades conocidas (`composer audit`)

## Variables de entorno (.env)
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

## Actualizar Composer (global)
```bash
composer self-update
composer --version
```

## Instalar dependencias
```bash
cd c:/Apache24/htdocs/api-rest-php
composer install
```

## Actualizar dependencias
```bash
composer update
```

## Verificar estado de dependencias
```bash
composer show
composer outdated
composer audit
```

## Verificacion recomendada de sintaxis
```bash
Get-ChildItem -Path . -Recurse -Filter *.php | ForEach-Object { php -l $_.FullName }
```

## Ejecutar migracion con un solo script PHP
El archivo `db/migrate.php` ejecuta automaticamente `db/test.sql`.

Ejecutar por CLI:
```bash
php db/migrate.php
```

Con credenciales personalizadas:
```bash
php db/migrate.php --host=localhost --user=root --password=
```

Tambien se puede ejecutar desde navegador:
```text
http://localhost/api-rest-php/db/migrate.php
```

Resultado esperado:
- Crea la base de datos `bd_test`
- Crea la tabla `usuario`
- Carga usuarios de prueba

[![Video](https://img.youtube.com/vi/p-I0_x5ApjA/0.jpg)](https://www.youtube.com/watch?v=p-I0_x5ApjA)  
[Ver demo](https://www.youtube.com/watch?v=p-I0_x5ApjA)
