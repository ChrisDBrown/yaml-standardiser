.PHONY: all
default: all;

unit:
	vendor/bin/phpunit tests

standards:
	vendor/bin/phpcs src tests --standard=ruleset.xml --extensions=php -sp --colors

static:
	vendor/bin/phpstan analyse src --level=max

all: unit standards static
