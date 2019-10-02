ARG base_image=php:7.2-cli
FROM $base_image

# Installing additional tools
RUN apt-get update \
 && apt-get install -y git unzip \
 && rm -rf /var/lib/apt/lists/*

# Installing composer as a globally available system command.
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
 && php composer-setup.php \
 && php -r "unlink('composer-setup.php');" \
 && mv composer.phar /usr/local/bin/composer
