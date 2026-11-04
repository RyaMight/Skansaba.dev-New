# ==================================================================
# Skansaba.dev — Production image (PHP-FPM + Nginx + Supervisor)
# Build:  docker compose build
# Run:    docker compose up -d
# ==================================================================

# ---------- Stage 1: composer deps (vendor) ----------
FROM composer:2 AS vendor
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install \
    --no-dev \
    --no-scripts \
    --no-autoloader \
    --prefer-dist \
    --no-interaction \
    --no-progress

COPY app/ app/
COPY bootstrap/ bootstrap/
COPY config/ config/
COPY database/ database/
COPY public/ public/
COPY resources/ resources/
COPY routes/ routes/
COPY artisan artisan

RUN composer dump-autoload --optimize --no-dev --no-scripts

# ---------- Stage 2: frontend assets (Vite build) ----------
FROM node:22-alpine AS assets
WORKDIR /app
COPY package.json package-lock.json ./
RUN npm ci
COPY vite.config.js ./
COPY resources/ resources/
RUN npm run build

# ---------- Stage 3: runtime ----------
FROM php:8.3-fpm-alpine AS runtime

ARG WWWGROUP=1000
ARG WWWUSER=1000

# Supervisor untuk menjalankan php-fpm + nginx sekaligus
RUN apk add --no-cache \
        nginx \
        supervisor \
        curl \
        mysql-client \
        dcron \
        libzip-dev \
        libpng-dev \
        libjpeg-turbo-dev \
        freetype-dev \
        oniguruma-dev \
        icu-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
        pdo_mysql \
        mbstring \
        zip \
        exif \
        pcntl \
        bcmath \
        gd \
        intl \
        opcache \
    && apk del libpng-dev libjpeg-turbo-dev freetype-dev oniguruma-dev icu-dev

# PHP production config + opcache
RUN cp /usr/local/etc/php/php.ini-production /usr/local/etc/php/php.ini \
    && { \
        echo "opcache.enable=1"; \
        echo "opcache.enable_cli=1"; \
        echo "opcache.validate_timestamps=0"; \
        echo "opcache.jit=tracing"; \
        echo "opcache.jit_buffer_size=64M"; \
        echo "memory_limit=256M"; \
        echo "upload_max_filesize=10M"; \
        echo "post_max_size=12M"; \
    } > /usr/local/etc/php/conf.d/skansaba.ini

WORKDIR /var/www/html

# Composer binary dari stage 1 (untuk artisan migrate)
COPY --from=vendor /usr/bin/composer /usr/local/bin/composer

# Kode aplikasi
COPY --chown=www-data:www-data . /var/www/html
COPY --from=vendor --chown=www-data:www-data /app/vendor /var/www/html/vendor
COPY --from=assets --chown=www-data:www-data /app/public/build /var/www/html/public/build

# Nginx config: terima request, teruskan ke php-fpm via unix socket
RUN { \
        echo "server {"; \
        echo "    listen 80;"; \
        echo "    root /var/www/html/public;"; \
        echo "    index index.php;"; \
        echo "    client_max_body_size 12m;"; \
        echo "    location / {"; \
        echo "        try_files \$uri \$uri/ /index.php?\$query_string;"; \
        echo "    }"; \
        echo "    location ~ \.php$ {"; \
        echo "        fastcgi_pass unix:/var/run/php-fpm.sock;"; \
        echo "        fastcgi_index index.php;"; \
        echo "        fastcgi_param SCRIPT_FILENAME \$document_root\$fastcgi_script_name;"; \
        echo "        include fastcgi_params;"; \
        echo "    }"; \
        echo "    location ~ /\.(?!well-known).* { deny all; }"; \
        echo "}"; \
    } > /etc/nginx/http.d/default.conf \
    && { \
        echo "[global]"; \
        echo "error_log = /proc/self/fd/2"; \
        echo "[www]"; \
        echo "user = www-data"; \
        echo "group = www-data"; \
        echo "listen = /var/run/php-fpm.sock"; \
        echo "listen.owner = www-data"; \
        echo "listen.group = www-data"; \
        echo "pm = dynamic"; \
        echo "pm.max_children = 20"; \
        echo "pm.start_servers = 4"; \
        echo "pm.min_spare_servers = 2"; \
        echo "pm.max_spare_servers = 6"; \
        echo "catch_workers_output = yes"; \
    } > /usr/local/etc/php-fpm.d/www.conf

# Supervisor: php-fpm + nginx + scheduler cron
RUN { \
        echo "[supervisord]"; \
        echo "nodaemon=true"; \
        echo "user=root"; \
        echo "logfile=/dev/null"; \
        echo "logfile_maxbytes=0"; \
        echo "pidfile=/var/run/supervisord.pid"; \
        echo ""; \
        echo "[program:php-fpm]"; \
        echo "command=php-fpm"; \
        echo "autostart=true"; \
        echo "autorestart=true"; \
        echo "stdout_logfile=/dev/stdout"; \
        echo "stdout_logfile_maxbytes=0"; \
        echo "stderr_logfile=/dev/stderr"; \
        echo "stderr_logfile_maxbytes=0"; \
        echo ""; \
        echo "[program:nginx]"; \
        echo "command=nginx -g 'daemon off;'"; \
        echo "autostart=true"; \
        echo "autorestart=true"; \
        echo "stdout_logfile=/dev/stdout"; \
        echo "stdout_logfile_maxbytes=0"; \
        echo "stderr_logfile=/dev/stderr"; \
        echo "stderr_logfile_maxbytes=0"; \
        echo ""; \
        echo "[program:scheduler]"; \
        echo "command=php /var/www/html/artisan schedule:work"; \
        echo "autostart=true"; \
        echo "autorestart=true"; \
        echo "user=www-data"; \
        echo "stdout_logfile=/dev/stdout"; \
        echo "stdout_logfile_maxbytes=0"; \
        echo "stderr_logfile=/dev/stderr"; \
        echo "stderr_logfile_maxbytes=0"; \
    } > /etc/supervisord.conf

# Entry point: bootstrap .env, storage, key, migrate, lalu supervisord
COPY --chmod=755 docker/entrypoint.sh /usr/local/bin/entrypoint.sh
ENTRYPOINT ["entrypoint.sh"]

EXPOSE 80
CMD ["supervisord", "-c", "/etc/supervisord.conf"]
