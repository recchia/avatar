Avatar Project
===========

Esta API tiene como funcion servir y gestionar Avatares.  El servicio debe permitir servir los avatares en distintos tamaños y tipos de imagen, cargar avatares asociados a los emails, borrar los avatares,  y servir avatares por default en caso de que se pida uno inexistente.

Instalacion
========

Para realizar la instalación del servicio, es necesario contar con una base de datos (MySQL, Postgres, Oracle) un servidor web ó una version de PHP igual o superior a 5.4 para utilizar el servidor embebido.

Si ya se tienen los requerimeintos de software, proceder a ejecutar los siguientes comandos:

```bash
git clone https://github.com/recchia/avatar
```
```bash
cd avatar
```

Ajustar las credenciales de base de datos y servidor de correo en el archivo ***app/config/parameters.yml***

```bash
php composer install
```
```bash
php bin/console doctrine:database:create
php bin/console doctrine:schema:update --force
```

Asignar permisos de escritura a las carpetas ***var/cache, var/log, var/avatars y var/sessions***

Ejecutar el servidor
```bash
php bin/console server:run
```