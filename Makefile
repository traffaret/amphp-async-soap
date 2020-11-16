command ?=

composer_bin := composer
docker_bin := docker
docker_container := amphp-soap
root_dir := "$$(pwd)"

composer_cache := /usr/.composer/cache

.PHONY: build
up: Dockerfile
	$(docker_bin) volume create vendor-data 1>/dev/null
	$(docker_bin) volume create vendor-cache 1>/dev/null
	$(docker_bin) \
		build --build-arg composer_cache=$(composer_cache) \
		-t amphp-soap .

.PHONY: up
run: Dockerfile
	$(docker_bin) run --rm -it \
   		-v $(root_dir)/src:/usr/app/src \
   		-v $(root_dir)/tests:/usr/app/tests \
   		-v $(root_dir)/phpunit.xml.dist:/usr/app/phpunit.xml.dist \
   		-v $(root_dir)/phpcs.xml.dist:/usr/app/phpcs.xml.dist \
   		-v $(root_dir)/composer.json:/usr/app/composer.json \
   		-v $(root_dir)/composer.lock:/usr/app/composer.lock \
   		-v vendor-data:/usr/app/vendor \
   		-v vendor-cache:$(composer_cache) \
   		--name amphp-soap \
   	amphp-soap $(command)

.PHONY: prune
prune:
	$(docker_bin) rmi -f amphp-soap
	$(docker_bin) volume rm -f vendor-cache vendor-data

.PHONY: composer
composer: composer.json composer.lock

.PHONY: composer-install
composer-install: composer
	@make run command="composer install -n"
	$(composer_bin) install -n

.PHONY: composer-update
composer-update: composer
	@make run command="composer update -n"
	$(composer_bin) install -n

.PHONY: composer-require
composer-require: composer
	@make run command="composer require -n --no-install $(command)"
	$(composer_bin) install -n
