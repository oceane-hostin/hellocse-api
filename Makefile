SAIL = ./vendor/bin/sail

args = `arg="$(filter-out $(firstword $(MAKECMDGOALS)),$(MAKECMDGOALS))" && echo $${arg:-${1}}`

%:
	@:

## —— Docker ————————————————————————————————————————————————————————————————
up:
	@$(SAIL) up -d

down:
	@$(SAIL) down

## —— Artisan ————————————————————————————————————————————————————————————————
controller:
	@$(SAIL) artisan make:controller --api

entity:
	@$(SAIL) artisan make:model -m

factory:
	@$(SAIL) artisan make:factory

migrate:
	@$(SAIL) artisan migrate:fresh

prepare-testing:
	@$(SAIL) artisan migrate --env=testing --seed

reset-testing:
	@$(SAIL) artisan migrate:rollback --env=testing

resource:
	@$(SAIL) artisan make:resource

resource-collection:
	@$(SAIL) artisan make:resource --collection

request:
	@$(SAIL) artisan make:request

route-show:
	@$(SAIL) artisan route:list

route-clear:
	@$(SAIL) artisan route:clear

seed:
	@$(SAIL) artisan db:seed

seeder:
	@$(SAIL) artisan make:seeder

test:
	@$(SAIL) artisan make:test --unit


## —— Local env ————————————————————————————————————————————————————————————————
local-rights:
	sudo chmod -R 777 .
