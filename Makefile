.PHONY: init-project php-bash mysql-bash permision-all up down ps logs

init-project:
	docker-compose -f ./docker-compose.yml exec wecode sh /var/www/html/init.project
php-bash:
	docker-compose -f ./docker-compose.yml exec wecode bash
mysql-bash:
	docker-compose -f ./docker-compose.yml exec db-mysql bash

permision-all:
	docker-compose -f ./docker-compose.yml exec wecode sh -c 'chown 1000:1000 -R ./*'
	docker-compose -f ./docker-compose.yml exec wecode sh -c 'chown 1000:1000 -R ./.*'
up:
	docker-compose -f ./docker-compose.yml up -d
down:
	docker-compose -f ./docker-compose.yml down
ps:
	docker-compose -f ./docker-compose.yml ps -a
logs:
	docker-compose -f ./docker-compose.yml logs -f