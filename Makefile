DOCKER_COMPOSE  = docker-compose

SYMFONY         = bin/console
COMPOSER        = composer
YARN            = yarn

.DEFAULT_GOAL := help
help:
	@grep -E '(^[a-zA-Z_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'
.PHONY: help

###-------------------------#
###      Project            #
###-------------------------#

install: vendor db ## Install and start the project

reset: clean install ## Stop and start a fresh install of the project

start: ## Start the project
	symfony server:start

composer.lock: composer.json # rules based on files
	$(COMPOSER) update --lock --no-scripts --no-interaction

vendor: composer.lock
	composer install

.PHONY: build kill install reset start stop clean no-docker vendor

###-------------------------#
###      Tools              #
###-------------------------#

clear: ## Remove all the cache, the logs and the built assets
	rm -rf var/cache/*
	rm -rf var/log/*

clean: clear ## clean and remove vendor and node_modules
	rm -rf public/build
	rm -rf public/bundles
	rm -rf vendor

.PHONY: clean clear

###-------------------------#
###      Databases          #
###-------------------------#

db: vendor db-create db-migrate ## Build DBdatabase and load fixtures

db-reset: vendor db-drop db ## Reset the database and load fixtures

db-drop:
	$(SYMFONY) doctrine:database:drop --force

db-create:
	$(SYMFONY) doctrine:database:create

db-diff: vendor  ## Generate a new doctrine migration
	$(SYMFONY) doctrine:migrations:diff --formatted

db-migrate: vendor  ## Migrate a new doctrine migration
	$(SYMFONY) doctrine:migrations:migrate --no-interaction

db-validate-schema: vendor ## Validate the doctrine ORM mapping
	$(SYMFONY) doctrine:schema:validate

.PHONY: db-reset db-drop db-create migration

###-------------------------#
###     Graph Workflow      #
###-------------------------#

graph-svg:
	$(SYMFONY) workflow:dump blog_publishing | dot -Tsvg -o ./public/assets/graph.svg

graph-png:
	$(SYMFONY) workflow:dump blog_publishing | dot -Tpng -o ./public/assets/graph.png
