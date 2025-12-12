FROM sail-8.4/app

# Copiar o projeto
COPY --chown=sail:sail . /var/www/html

WORKDIR /var/www/html

# Criar TODOS os diretórios necessários com permissões amplas ANTES de qualquer comando
RUN mkdir -p bootstrap/cache && \
    mkdir -p storage/logs && \
    mkdir -p storage/framework/cache && \
    mkdir -p storage/framework/sessions && \
    mkdir -p storage/framework/views && \
    mkdir -p storage/app && \
    chmod -R 777 bootstrap storage

# Instalar composer sem scripts
RUN composer install --no-interaction --no-dev --no-scripts --ignore-platform-req=ext-intl 2>&1 || true

# Criar arquivos de cache vazios
RUN touch bootstrap/cache/packages.php bootstrap/cache/services.php bootstrap/cache/config.php 2>/dev/null || true && \
    echo '<?php return array();' > bootstrap/cache/packages.php && \
    echo '<?php return array();' > bootstrap/cache/services.php && \
    echo '<?php return array();' > bootstrap/cache/config.php && \
    touch storage/logs/laravel.log && \
    chmod -R 777 bootstrap/cache storage/logs

# Correr package:discover com cache dir existente
RUN php artisan package:discover --ansi 2>&1 || true









