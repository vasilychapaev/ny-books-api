.PHONY: config-clear config-cache config-reset

clear:
	php artisan config:clear
	php artisan config:cache