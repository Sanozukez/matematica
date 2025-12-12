FROM sail-8.4/app:latest

WORKDIR /var/www/html

# Copy only source code first (excluding vendor, node_modules, lock files)
COPY --chown=sail:sail . .

# Create necessary directories with proper permissions BEFORE composer install
RUN mkdir -p bootstrap/cache storage/logs storage/framework/{cache,sessions,views} && \
    chown -R sail:sail bootstrap/cache storage && \
    chmod -R 775 bootstrap/cache storage

# Clean up lock files
RUN rm -f package-lock.json npm-shrinkwrap.json composer.lock

# Install dependencies (skip scripts to avoid cache path issues during build)
RUN composer install --no-interaction --no-ansi --no-scripts && \
    npm install && \
    npm run build

# Ensure proper ownership after build
RUN chown -R sail:sail /var/www/html
