FROM php:8.4-cli-alpine AS phpdev
RUN apk --no-cache add \
    unzip tzdata \
    $PHPIZE_DEPS linux-headers htop procps bash vim \
    && rm -rf /var/cache/apk*

COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/
RUN set -eux;  \
    install-php-extensions zip pcntl intl bcmath ds sockets \
    && rm -rf /tmp/*
ENV COMPOSER_HOME=/.composer
ENV TZ=UTC
COPY --from=composer/composer:2-bin /composer /usr/bin/composer

ARG DEVELOPER_UID=1000
RUN adduser -s /bin/sh -u ${DEVELOPER_UID} -D developer
USER developer
WORKDIR /app