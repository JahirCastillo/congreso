# Framework CodeIgniter 4

## ¿Qué es CodeIgniter?

CodeIgniter es un framework web full-stack para PHP que es ligero, rápido, flexible y seguro.
Más información se puede encontrar en el [sitio oficial](https://codeigniter.com).

Este repositorio contiene la versión distribuible del framework.

Más información sobre los planes para la versión 4 se puede encontrar en [CodeIgniter 4](https://forum.codeigniter.com/forumdisplay.php?fid=28) en los foros.

Puedes leer la [guía del usuario](https://codeigniter.com/user_guide/)
correspondiente a la última versión del framework.

## Cambio Importante con index.php

`index.php` ya no está en la raíz del proyecto. Se ha movido dentro de la carpeta *public*,
para una mejor seguridad y separación de componentes.

Esto significa que debes configurar tu servidor web para "apuntar" a la carpeta *public* de tu proyecto.
Una mala práctica sería apuntar tu servidor web a la raíz del proyecto y esperar ingresar a *public/...*, ya que el resto de tu lógica y el
framework estarán expuestos.

**Por favor** lee la guía del usuario para una mejor explicación de cómo funciona CI4.

## Requisitos del Servidor

Se requiere PHP versión 8.1 o superior, con las siguientes extensiones instaladas:

- [intl](http://php.net/manual/en/intl.requirements.php)
- [mbstring](http://php.net/manual/en/mbstring.installation.php)

> [!ADVERTENCIA]
> - La fecha de fin de vida para PHP 7.4 fue el 28 de noviembre de 2022.
> - La fecha de fin de vida para PHP 8.0 fue el 26 de noviembre de 2023.
> - Si todavía estás usando PHP 7.4 o 8.0, debes actualizar inmediatamente.
> - La fecha de fin de vida para PHP 8.1 será el 31 de diciembre de 2025.

Además, asegúrate de que las siguientes extensiones estén habilitadas en tu PHP:

- json (habilitado por defecto - no lo desactives)
- [mysqlnd](http://php.net/manual/en/mysqlnd.install.php) si planeas usar MySQL
- [libcurl](http://php.net/manual/en/curl.requirements.php) si planeas usar la biblioteca HTTP\CURLRequest

