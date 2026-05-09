# API Rest PHP - JWT 💻🐘
JSON Web Token (JWT) en una API REST en PHP proporciona una capa adicional de seguridad al permitir la autenticación de usuarios de manera eficiente y segura. Al utilizar JWT, los usuarios reciben un token único después de autenticarse correctamente, el cual luego se incluye en las solicitudes posteriores para verificar su identidad. 

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
- PHP 7.2 o superior con controladores PDO habilitados
- MySQL 5.7 / MariaDB 10.0

# Compatibilidad con PHP 8.3.15
El proyecto es compatible con PHP 8.3.15.

Validaciones realizadas:
- Verificacion de version de PHP en entorno local: 8.3.15
- Lint de todos los archivos PHP con `php -l` sin errores de sintaxis
- Dependencia JWT instalada (`firebase/php-jwt` v5.5.1) permite PHP >= 5.3.0

# Actualizar Composer y dependencias

## 1) Actualizar Composer (global)
```bash
composer self-update
composer --version
```

## 2) Caso actual de este repo (no hay composer.json en la raiz)
Este repositorio tiene carpeta `vendor/`, pero no `composer.json` en la raiz.
Para gestionar dependencias correctamente a futuro:

```bash
cd c:/Apache24/htdocs/api-rest-php
composer init
composer require firebase/php-jwt:^6.11
```

Luego instala/actualiza con:

```bash
composer install
composer update
```

## 3) Verificar vulnerabilidades y versiones desactualizadas
```bash
composer outdated
composer audit
```

## Nota importante sobre `firebase/php-jwt`
Si subes de v5.x a v6.x, cambia la forma de decodificar token.

Antes (v5):
```php
$decoded = JWT::decode($jwt, $key, ['HS256']);
```

Despues (v6):
```php
use Firebase\JWT\Key;

$decoded = JWT::decode($jwt, new Key($key, 'HS256'));
```

Si prefieres no tocar codigo todavia, puedes mantener v5.5.1 por compatibilidad inmediata.

[![Video](https://img.youtube.com/vi/p-I0_x5ApjA/0.jpg)](https://www.youtube.com/watch?v=p-I0_x5ApjA)  
[Ver demo](https://www.youtube.com/watch?v=p-I0_x5ApjA)
