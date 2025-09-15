
.DEFAULT_GOAL = help
APP_UID     ?= $(shell id -u)
DC = docker compose

help: ## Outputs this help screen
	@grep -E '(^[a-zA-Z0-9_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}{printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'

.PHONY: build
build: ## Up all
	@$(DC) build

.PHONY: up
up: ## Up all
	@$(DC) up -d

.PHONY: down
down: ## Down all
	@$(DC) down

.PHONY: shell
shell: ## Enter PHP application dev container
	@$(DC) exec -it php bash

.PHONY: init
init: ## Init all
	$(DC) exec php sh -c "composer install"
	$(DC) exec php sh -c "./vendor/bin/rr get-binary -l /app/bin"
	./init_manager_credentials.sh && echo
	$(DC) exec pulsar sh -c "bin/pulsar-admin namespaces set-retention public/default --time -1 --size -1"
	$(DC) exec pulsar sh -c "bin/pulsar-admin topics create persistent://public/default/poc"
# 	@$(DC) exec -it pulsar sh -c "bin/pulsar-admin topics set-retention persistent://public/default/my-topic --time -1 --size -1"

.PHONY: serve
serve: ## Run rr in PHP
	@$(DC) exec -it php sh -c "./bin/rr serve -c ./.rr.dev.yaml"

.PHONY: test-stress
test-stress: ## Run API stress test
	@$(DC) exec k6 sh -c 'k6 run /scripts/script.js'

.PHONY: pulsar-list
pulsar-list:
	@$(DC) exec -it pulsar sh -c "bin/pulsar-admin topics peek-messages --subscription my-subscription persistent://public/default/poc"