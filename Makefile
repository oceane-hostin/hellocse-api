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

migrate:
	@$(SAIL) artisan migrate:fresh

resource:
	@$(SAIL) artisan make:resource

resource-collection:
	@$(SAIL) artisan make:resource --collection

route-show:
	@$(SAIL) artisan route:list

route-clear:
	@$(SAIL) artisan route:clear

seed:
	@$(SAIL) artisan db:seed

seeder:
	@$(SAIL) artisan make:seeder

test:
	@$(SAIL) artisan install:api


## —— Local env ————————————————————————————————————————————————————————————————
local-rights:
	sudo chmod -R 777 .
