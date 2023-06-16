# setup on linux/mac
setup-on-unix:
	cp .env.example .env
	timeout 1
	composer up && composer i && npm up && npm i
	timeout 3
	php artisan key:generate

# setup on windows
setup-on-windows:
	copy .env.example .env
	timeout 1
	composer up && composer i && npm up && npm i
	timeout 3
	php artisan key:generate

up:
	composer up && composer i && npm up && npm i

push:
	git add .
	git commit -m "autopush"
	timeout 20
	git push origin master

pull:
	git pull origin master

run:
	php artisan serve

# with silent/ quite
runs:
	php artisan serve -q

test:
	php artisan test --without-tty

migrate:
	php artisan migrate:fresh

refresh:
	php artisan full-refresh

routes-list:
	php artisan route:list --compact

gitc:
	git config --global core.autocrlf input
	git config --global core.eol lf

docker-run:
	docker run -p 8080:8000 protani-181

docker-build:
	docker build -t protani-181 .