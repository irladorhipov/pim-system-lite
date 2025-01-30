up:
	docker-compose up -d

down:
	docker-compose down

rebuild:
	docker-compose down
	docker-compose up -d --build

clean:
	docker-compose down -v --remove-orphans

logs-app:
	docker-compose logs -f app

logs-webserver:
	docker-compose logs -f webserver

logs-db:
	docker-compose logs -f db

logs-redis:
	docker-compose logs -f redis

exec-app:
	docker-compose exec app bash

exec-webserver:
	docker-compose exec webserver sh

exec-db:
	docker-compose exec db bash

exec-redis:
	docker-compose exec redis sh

restart:
	docker-compose restart

test-parser:
	vendor/bin/phpunit tests/Parser/ParserTest.php
