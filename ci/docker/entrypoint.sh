#!/usr/bin/env bash

if [[ $APP_ENV == "production" ]] || [[ $APP_ENV == 'staging' ]]; then
    php artisan optimize
    php artisan config:clear
    php artisan event:cache
    php artisan route:cache
    php artisan view:cache

    php artisan migrate --force

    apache2-foreground
else
        apache2-foreground
fi
