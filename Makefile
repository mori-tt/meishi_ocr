run:
	make copy-env-if-not-exist
	make install
	make down
	make up

up:
	./vendor/bin/sail up

down:
	./vendor/bin/sail down

build-clear:
	./vendor/bin/sail build --no-cache

migrate:
	./vendor/bin/sail exec laravel.test php artisan migrate

seed:
	php artisan db:seed --class=initBusinessCardSeeder

driver:
	cat driver.php | php artisan tinker

copy-env-if-not-exist:
	[ ! -f .env ] && cp .env.example .env || true

install:
	docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v .:/var/www/html \
    -w /var/www/html \
    laravelsail/php83-composer:latest \
    composer install --ignore-platform-reqs

tunnel:
	devtunnel host --port-numbers 20080 --allow-anonymous

sqlite:
	sqlite3 ./database/database.sqlite
