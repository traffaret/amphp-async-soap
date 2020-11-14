command ?=

composer_bin := composer
docker_bin := docker
docker_container := amphp-soap
root_dir := "$$(pwd)"

composer_cache := /usr/.composer/cache

.PHONY: up
up: Dockerfile
	$(docker_bin) \
		build --build-arg composer_cache=$(composer_cache) \
		-t amphp-soap . \
    && $(docker_bin) run -d \
		-v $(root_dir)/src:/usr/app/src \
		-v $(root_dir)/tests:/usr/app/tests \
		-v $(root_dir)/phpunit.xml.dist:/usr/app/phpunit.xml.dist \
		-v $(root_dir)/phpcs.xml.dist:/usr/app/phpcs.xml.dist \
		-v $(root_dir)/composer.json:/usr/app/composer.json \
		-v $(root_dir)/composer.lock:/usr/app/composer.lock \
		-v vendor-data:/usr/app/vendor \
		-v vendor-cache:$(composer_cache) \
		--name amphp-soap \
    $(docker_container)

.PHONY: down
down: Dockerfile
	$(docker_bin) rm -f $(docker_container)

.PHONY: exec
exec:
	$(docker_bin) run -it $(docker_container) $(command)

.PHONY: composer
composer: composer.json composer.lock

.PHONY: composer-install
composer-install: composer
	@make exec command="composer install -n"
	$(composer_bin) install -n

.PHONY: composer-update
composer-update: composer
	@make exec command="composer update -n"
	$(composer_bin) install -n

.PHONY: composer-require
composer-require: composer
	@make exec command="composer require -n --no-install $(command)"
	@make composer-install
