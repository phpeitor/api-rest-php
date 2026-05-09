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
- PHP 8.3 o superior con controladores PDO habilitados
- MySQL 5.7 / MariaDB 10.0

# Migracion completada (PHP 8.3 + Composer)

Estado actual del proyecto:
- Composer inicializado en la raiz del repo
- Archivo `composer.json` agregado
- Archivo `composer.lock` generado
- Dependencia JWT actualizada a `firebase/php-jwt` v7.0.5
- Auditoria de Composer sin vulnerabilidades conocidas (`composer audit`)

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

[![Video](https://img.youtube.com/vi/p-I0_x5ApjA/0.jpg)](https://www.youtube.com/watch?v=p-I0_x5ApjA)  
[Ver demo](https://www.youtube.com/watch?v=p-I0_x5ApjA)
