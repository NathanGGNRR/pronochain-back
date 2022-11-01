FROM nginx:stable-alpine

RUN mkdir -p /etc/nginx/templates

COPY docker/conf/default.conf.template /etc/nginx/templates

WORKDIR /usr/share/nginx/html

COPY ./public ./public
