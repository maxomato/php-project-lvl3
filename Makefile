vssh:
	vagrant ssh -- -t "cd /vagrant ; bash"

install:
	composer install

run:
	php -S 0.0.0.0:8000 -t public

lint:
	composer run-script phpcs -- --standard=PSR12 app bootstrap public routes tests config

test:
	composer run-script phpunit tests

test-coverage:
	composer run-script phpunit -- --coverage-clover clover.xml tests

log:
	tail -f storage/logs/lumen.log

migrate:
	php artisan migrate --force

release: install migrate


