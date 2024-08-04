FROM php:8.3.9-cli-alpine3.20

USER root

ADD  ./install-php-extensions /usr/local/bin/

RUN chmod +x /usr/local/bin/install-php-extensions

# 在此处添加其他扩展：
RUN install-php-extensions \
  redis \
  mongodb \
  memcached \
  pcntl \
  pdo_mysql \
  sockets \
  swoole \
  && apk add supervisor \
  && apk add nodejs npm \
  && apk add tzdata \
  && apk add libmemcached-dev \
  && apk add zlib-dev \
  && npm install pnpm -g \
  && mkdir /var/pnpm \
  && apk add wait4x \
  && pnpm config set store-dir /var/pnpm \
  && cp /usr/share/zoneinfo/Asia/Shanghai /etc/localtime \
  && echo "Asia/Shanghai" > /etc/timezone


