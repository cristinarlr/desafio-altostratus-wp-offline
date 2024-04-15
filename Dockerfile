#pulls the wordpress docker image to get lastest phpX.X-apache version
FROM wordpress:latest

#expose PORT 8080
EXPOSE 8080

#database settings
#ENV WORDPRESS_DB_NAME "my-database"
#ENV WORDPRESS_DB_USER "crramire"
#ENV WORDPRESS_DB_PASSWORD "changeme"
#ENV WORDPRESS_DB_HOST "35.193.241.135"

# Copiar el archivo de configuración personalizado de WordPress
#COPY wp-config.php /var/www/html/wp-config.php
