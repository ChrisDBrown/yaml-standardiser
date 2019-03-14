.PHONY: all
default: all;

standards:
	vendor/bin/phpcs src --standard=ruleset.xml --extensions=php -sp --colors

static:
	vendor/bin/phpstan analyse src --level=max

all: standards static
