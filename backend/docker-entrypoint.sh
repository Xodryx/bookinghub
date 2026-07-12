#!/bin/sh
# Ensures php-fpm (www-data) can write to storage and cache
# on every container startup, even on Windows bind mounts.
set -e

chown -R www-data:www-data storage bootstrap/cache 2>/dev/null || true
chmod -R 775 storage bootstrap/cache 2>/dev/null || true

# Run the given command (default: php-fpm)
exec "$@"
