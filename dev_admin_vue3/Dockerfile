FROM alpine:3.19.1

USER root

RUN apk add nodejs \
  && apk add npm \
  && npm install pnpm -g \
  && apk add xdg-utils \ 
  && mkdir /pnpm_store \
  && pnpm config set store-dir /pnpm_store


ENV PNPM_STORE_PATH /pnpm
