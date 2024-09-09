
# Use the official PHP image.
# https://hub.docker.com/_/php
FROM php:8.1-apache-bullseye

## PYTHON INSTALL
RUN apt-get update

# Configure PHP for Cloud Run.
# Precompile PHP code with opcache.
RUN docker-php-ext-install -j "$(nproc)" opcache
#RUN docker-php-ext-install curl
RUN set -ex; \
  { \
    echo "; Cloud Run enforces memory & timeouts"; \
    echo "memory_limit = -1"; \
    echo "max_execution_time = 0"; \
    echo "; File upload at Cloud Run network limit"; \
    echo "upload_max_filesize = 32M"; \
    echo "post_max_size = 32M"; \
    echo "; Configure Opcache for Containers"; \
    echo "opcache.enable = On"; \
    echo "opcache.validate_timestamps = Off"; \
    echo "; Configure Opcache Memory (Application-specific)"; \
    echo "opcache.memory_consumption = 32"; \
  } > "$PHP_INI_DIR/conf.d/cloud-run.ini"

##NEW RELIC INSTALL
#RUN apt-get install sudo -y
#RUN curl -s -O https://download.newrelic.com/php_agent/archive/10.12.0.1/newrelic-php5-10.12.0.1-linux.tar.gz
#RUN gzip -dc newrelic-php5-10.12.0.1-linux.tar.gz | tar xf -
#RUN sudo NR_INSTALL_USE_CP_NOT_LN=1 NR_INSTALL_SILENT=true NR_INSTALL_KEY=eu01xxd702aef43e61a27b1d81532cf3FFFFNRAL ./newrelic-php5-10.12.0.1-linux/newrelic-install install
#RUN sudo sed -i -e "s/newrelic.appname[[:space:]]=[[:space:]].*/newrelic.appname = \"api-webserver\"/" $(sudo php -r "echo(PHP_CONFIG_FILE_SCAN_DIR);")/newrelic.ini

##POSTMAN INSIGHTS
RUN bash -c "$(curl -L https://releases.observability.postman.com/scripts/install-postman-insights-agent.sh)"
#RUN POSTMAN_API_KEY=${{ secrets.POSTMAN_TOKEN_INSIGHTS }} postman-insights-agent ec2 setup --project svc_0BEE5U51CTgPjJfAYu9JJC

# Copy in custom code from the host machine.
WORKDIR /var/www/html
COPY . ./

# CREATE THE FOLDER FOR THE DATABASE
RUN mkdir -p /var/www/html/data
RUN chmod -R 777 /var/www/html/data

# Use the PORT environment variable in Apache configuration files.
# https://cloud.google.com/run/docs/reference/container-contract#port
RUN a2enmod rewrite
RUN cp src/.apache /etc/apache2/sites-available/000-default.conf
RUN sed -i 's/80/${PORT}/g' /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf

# Configure PHP for development.
# Switch to the production php.ini for production operations.
# RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"
# https://github.com/docker-library/docs/blob/master/php/README.md#configuration
RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"