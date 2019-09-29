.PHONY: test
test: phpstan phan

phpstan:
	vendor/bin/phpstan analyse --level 7 src

phan:
	vendor/bin/phan
