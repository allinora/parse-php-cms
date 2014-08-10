default:
	@echo Please read the Makefile

install:
	mkdir -p tmp/uploads tmp/cache tmp/logs tmp/sessions tmp/smarty_compile tmp/smarty_cache
	chmod -R 777 tmp/uploads tmp/cache tmp/logs tmp/sessions tmp/smarty_compile
	php bin/composer.phar install
	@test -d vendor/allinora/simple-framework/.git && (git --git-dir vendor/allinora/simple-framework/.git  pull origin master)
	@test -f config/config.php || (cp config/config.template.php config/config.php  && echo Configuration file is config/config.php. Please edit it with your Parse informations)


clean:
	rm -rf tmp
	find . -type f -name ".#*" -exec rm {} \;
	find . -type f -name "*~" -exec rm {} \;
	find . -type f -name "Thumbs.db" -exec rm {} \;
	find . -type f -name ".DS_Store" -exec rm {} \;
	rm -rf tmp/uploads tmp/cache tmp/logs tmp/sessions tmp/smarty_compile tmp/smarty_cache

distclean: clean
	rm -rf vendor composer.lock

run:
	php -S 0.0.0.0:8080 -t public