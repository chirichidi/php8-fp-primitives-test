RUNTIME_TAG='chirichidi/php8-fp-primitives-test'

update: \
	update-code-only \
	build

update-code-only:
	git reset --hard
	git pull

install-composer: install-composer-dep
	curl https://getcomposer.org/installer -o /tmp/composer-setup.php && \
		sudo php /tmp/composer-setup.php --install-dir=/usr/bin --filename=composer && \
		rm -f /tmp/composer-setup.php && \
		composer --version

install-composer-dep:
	yum install php-xml unzip -y

build: \
	runtime-build \
	composer-install-in-runtime

runtime-build:
	docker build \
		--tag ${RUNTIME_TAG} \
		./env/docker/runtime-dev

composer-install-in-runtime:
	docker run --rm -it \
		-v $(PWD):/opt/project \
		-v ~/.composer:/root/.composer \
 		${RUNTIME_TAG} composer -vvv install -d /opt/project

composer-update-in-runtime:
	docker run --rm -it \
		-v $(PWD):/opt/project \
		-v ~/.composer:/root/.composer \
 		${RUNTIME_TAG} composer -vvv update -d /opt/project