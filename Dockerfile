#pulls the wordpress docker image to get lastest phpX.X-apache version
FROM wordpress:latest

#expose PORT 8080
EXPOSE 8080

#database settings
ENV WORDPRESS_DB_NAME "my-database"
ENV WORDPRESS_DB_USER "crramire"
ENV WORDPRESS_DB_NAME "changeme"
ENV WORDPRESS_DB_NAME "35.193.241.135"
