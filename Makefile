COMPOSER = composer

##
## Tests
## -----
##

test: ## Run unit and functional tests
test: vendor
	php ./vendor/bin/phpunit

test-coverage: vendor
	php ./vendor/bin/phpunit  --coverage-html build/coverage

composer.lock: composer.json
	$(COMPOSER) update --lock --no-scripts --no-interaction

vendor: composer.lock
	$(COMPOSER) install

composer-require:
	$(COMPOSER) require $(filter-out $@,$(MAKECMDGOALS))

composer-require-dev:
	$(COMPOSER) require --dev $(filter-out $@,$(MAKECMDGOALS))

composer-update:
	$(COMPOSER) update

##
## Quality assurance
## -----------------
##
tools:
	mkdir $@

tools/local-php-security-checker-%: tools
	sh -c "rm -f tools/local-php-security-checker-*"
	sh -c "curl -LSso $@ \"https://github.com/fabpot/local-php-security-checker/releases/download/v$*/local-php-security-checker_$*_linux_amd64\""
	sh -c "chmod +x tools/local-php-security-checker-*"

.PHONY: local-php-security-checker
local-php-security-checker: tools/local-php-security-checker-2.0.3
	$< $(arguments)

.PHONY: security
security:
	$(MAKE) local-php-security-checker arguments=""

tools/phpmd/composer.lock: tools/phpmd/composer.json
	$(COMPOSER) --working-dir=tools/phpmd update

tools/phpmd/vendor: composer.lock
	$(COMPOSER) --working-dir=tools/phpmd install

.PHONY: phpmd
phpmd: tools/phpmd/vendor
	tools/phpmd/vendor/bin/phpmd $(arguments)

.PHONY: apply-phpmd
apply-phpmd:
	$(MAKE) phpmd arguments="src text .phpmd.xml"

.PHONY: update-phpmd
update-phpmd:
	$(COMPOSER) --working-dir=tools/phpmd update

tools/phpcpd/composer.lock: tools/phpcpd/composer.json
	$(COMPOSER) --working-dir=tools/phpcpd update

tools/phpcpd/vendor: composer.lock
	$(COMPOSER) --working-dir=tools/phpcpd install

.PHONY: phpcpd
phpcpd: tools/phpcpd/vendor
	tools/phpcpd/vendor/bin/phpcpd $(arguments)

.PHONY: apply-phpcpd
apply-phpcpd:
	$(MAKE) phpcpd arguments="src --exclude src/Report --exclude src/Entity/Contract/ContractBeneficiary.php --exclude src/Broker/Security/StelloSaleBrokerRoleGuesser.php --exclude src/Broker/Security/StelloManagerBrokerRoleGuesser.php --exclude src/Administrator/Security/AdministratorRoleGuesser.php"

.PHONY: update-phpcpd
update-phpcpd:
	$(COMPOSER) --working-dir=tools/phpcpd update

tools/phpstan/composer.lock: tools/phpstan/composer.json
	$(COMPOSER) --working-dir=tools/phpstan update

tools/phpstan/vendor: composer.lock
	$(COMPOSER) --working-dir=tools/phpstan install

.PHONY: phpstan
phpstan: tools/phpstan/vendor
	tools/phpstan/vendor/bin/phpstan $(arguments)

.PHONY: apply-phpstan
apply-phpstan:
	$(MAKE) phpstan arguments="analyse --memory-limit=4G -l 9 -c .phpstan.neon src tests"

.PHONY: update-phpstan
update-phpstan:
	$(COMPOSER) --working-dir=tools/phpstan update

tools/php-cs-fixer/composer.lock: tools/php-cs-fixer/composer.json
	$(COMPOSER) --working-dir=tools/php-cs-fixer update

tools/php-cs-fixer/vendor: composer.lock
	$(COMPOSER) --working-dir=tools/php-cs-fixer install

.PHONY: php-cs-fixer
php-cs-fixer: tools/php-cs-fixer/vendor
	tools/php-cs-fixer/vendor/bin/php-cs-fixer $(arguments)

.PHONY: check-php-cs
check-php-cs:
	$(MAKE) php-cs-fixer arguments="fix --dry-run --using-cache=no --verbose --diff"

.PHONY: apply-php-cs
apply-php-cs:
	$(MAKE) php-cs-fixer arguments="fix --using-cache=no --verbose --diff"

.PHONY: update-php-cs-fixer
update-php-cs-fixer:
	$(COMPOSER) --working-dir=tools/php-cs-fixer update

pre-commit: apply-phpmd apply-phpcpd apply-php-cs apply-phpstan

.DEFAULT_GOAL := help

help:
	@grep -E '(^[a-zA-Z_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'
.PHONY: help