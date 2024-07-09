SAIL = ./vendor/bin/sail

args = `arg="$(filter-out $(firstword $(MAKECMDGOALS)),$(MAKECMDGOALS))" && echo $${arg:-${1}}`

up:
	@$(SAIL) up -d

down:
	@$(SAIL) down

migrate:
	@$(SAIL) artisan migrate

entity:
	@$(SAIL) artisan make:model -m $(args)


local-rights:
	sudo chmod -R 777 .
