vssh:
	vagrant ssh -- -t "cd /vagrant ; bash"

run:
	php -S 0.0.0.0:8000 -t public

lint:
	composer run-script phpcs -- --standard=PSR12 app bootstrap public resources routes tests

test:
	composer run-script phpunit tests

log:
	tail -f storage/logs/lumen.log
