
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

.PHONY: test-stress
test-stress: ## Run API stress test
	@$(DC) exec k6 sh -c 'k6 run /scripts/script.js'