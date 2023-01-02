FROM php:8.0-apache

# DIRETÓRIO DO PROJETO
WORKDIR /var/www/html

# PERMITINDO ACESSO À PASTA AO APACHE
RUN chown -R www-data:www-data /var/www/

# CONFIGURAÇÕES APACHE PARA O LARAVEL
COPY  $PWD/000-default.conf /etc/apache2/sites-available/
COPY $PWD/apache2.conf /etc/apache2/

# ACEITANDO ROTAS LARAVEL
RUN sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf
RUN a2enmod rewrite
RUN service apache2 restart

# REPOSITORIO PARA FAZER A INSTALAÇÃO DAS DEPENDENCIAS
ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

# DEPENDENCIAS
RUN apt update && \
    chmod uga+x /usr/local/bin/install-php-extensions && sync && \
    install-php-extensions apcu \
    mysqli \
    pdo_mysql \
    zip
