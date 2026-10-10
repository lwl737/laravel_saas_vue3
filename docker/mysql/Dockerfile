FROM mysql:8.0

USER root

COPY ./start.sh /docker-entrypoint-initdb.d/

RUN chmod +x /docker-entrypoint-initdb.d/start.sh
