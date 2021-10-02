dev:
dev: install serve

test:
test: install test

reports:
reports: install reports

queue:
queue: install queue

install:
	composer install

serve:
	symfony serve

test:
	php ./bin/phpunit

reports:
	./bin/console cron:report

queue:
	./bin/console messenger:consume async
