FROM nginx:1.23.0-alpine as production-stage
COPY --chown=root:root . /var/www/html
COPY --chown=root:root backend.conf /etc/nginx/conf.d/default.conf
EXPOSE 80 22
CMD ["nginx", "-g", "daemon off;"]
