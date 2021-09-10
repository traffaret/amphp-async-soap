command ?=

COMPOSER := composer
DOCKER := docker
MAKE := make

docker_container := amphp-soap
root_dir := "$$(pwd)"

composer_cache := /usr/.composer/cache

.PHONY: up
up: Dockerfile
	$(DOCKER) volume create $(docker_container)_svendor-data 2>/dev/null
	$(DOCKER) build -t $(docker_container) .
	$(MAKE) composer-install

.PHONY: down
down: Dockerfile
	$(DOCKER) stop $(docker_container)
	$(DOCKER) rm -f $(docker_container)

.PHONY: run
run:
	$(DOCKER) run --rm -it \
   		-v $(root_dir)/src:/usr/app/src \
   		-v $(root_dir)/tests:/usr/app/tests \
   		-v $(root_dir)/phpunit.xml.dist:/usr/app/phpunit.xml.dist \
   		-v $(root_dir)/phpcs.xml.dist:/usr/app/phpcs.xml.dist \
   		-v $(root_dir)/composer.json:/usr/app/composer.json \
   		-v $(root_dir)/composer.lock:/usr/app/composer.lock \
   		-v $(docker_container)_vendor-data:/usr/app/vendor \
   		--name $(docker_container) \
   	$(docker_container) $(command)

.PHONY: prune
prune: Dockerfile
	$(DOCKER) rmi -f $(docker_container)
	$(DOCKER) volume rm -f $(docker_container)_vendor-data

.PHONY: composer
composer: composer.json composer.lock

.PHONY: composer-install
composer-install: composer
	$(MAKE) run command="composer install -n"
	$(COMPOSER) install -n --no-scripts --no-plugins --ignore-platform-reqs 2>&1

.PHONY: composer-update
composer-update: composer
	$(MAKE) run command="composer update -n"
	$(COMPOSER) install -n --no-scripts --no-plugins --ignore-platform-reqs 2>&1

.PHONY: composer-require
composer-require: composer
	$(MAKE) run command="composer require -n --no-install $(command)"
	$(COMPOSER) install -n --no-plugins --no-scripts --ignore-platform-reqs 2>&1

.PHONY: test
test:
	$(MAKE) run command="composer test"
