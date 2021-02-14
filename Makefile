############################
## Docker
############################
state: docker-ps
up: docker-up
down: docker-down
restart: docker-down docker-up
init: docker-down-clear manager-clear docker-pull docker-build docker-up manager-init

############################
## Update
############################
update: manager-composer-update

############################
## Test
############################
test: manager-test
test-unit: manager-test-unit
test-unit-coverage: manager-test-unit-coverage
test-functional: manager-test-functional

############################
## Log
############################
logs: docker-logs

cache-clear: manager-cache-clear

############################
docker-ps:
	docker-compose ps

docker-up:
	docker-compose up -d

docker-down:
	docker-compose down --remove-orphans

docker-down-clear:
	docker-compose down -v --remove-orphans

docker-pull:
	docker-compose pull

docker-build:
	docker-compose build

docker-logs:
	docker-compose logs -f

manager-init: manager-composer-install manager-assets-install manager-oauth-keys manager-wait-db manager-migrations manager-fixtures manager-ready

manager-clear:
	docker run --rm -v ${PWD}/manager:/app --workdir=/app alpine rm -f .ready

manager-composer-install:
	docker-compose run --rm manager-php-cli composer install

manager-composer-update:
	docker-compose run --rm manager-php-cli composer update

manager-composer-outdated:
	docker-compose run --rm manager-php-cli composer outdated

manager-assets-install:
	docker-compose run --rm manager-node yarn install
	docker-compose run --rm manager-node npm rebuild node-sass

manager-oauth-keys:
	docker-compose run --rm manager-php-cli mkdir -p var/oauth
	docker-compose run --rm manager-php-cli openssl genrsa -out var/oauth/private.key 2048
	docker-compose run --rm manager-php-cli openssl rsa -in var/oauth/private.key -pubout -out var/oauth/public.key
	docker-compose run --rm manager-php-cli chmod 644 var/oauth/private.key var/oauth/public.key

manager-wait-db:
	until docker-compose exec -T manager-postgres pg_isready --timeout=0 --dbname=app ; do sleep 1 ; done

manager-migrations:
	docker-compose run --rm manager-php-cli ./bin/console doctrine:migrations:migrate --no-interaction

manager-fixtures:
	docker-compose run --rm manager-php-cli ./bin/console doctrine:fixtures:load --no-interaction

manager-cache-clear:
	docker-compose run --rm manager-php-cli ./bin/console cache:clear

manager-ready:
	docker run --rm -v ${PWD}/manager:/app --workdir=/app alpine touch .ready

manager-assets-dev:
	docker-compose run --rm manager-node npm run dev

manager-test:
	docker-compose run --rm manager-php-cli php bin/phpunit

manager-test-coverage:
	docker-compose run --rm manager-php-cli php bin/phpunit --coverage-clover var/clover.xml --coverage-html var/coverage

manager-test-unit:
	docker-compose run --rm manager-php-cli php bin/phpunit --testsuite=unit

manager-test-unit-coverage:
	docker-compose run --rm manager-php-cli php bin/phpunit --testsuite=unit --coverage-clover var/clover.xml --coverage-html var/coverage

manager-test-functional:
	docker-compose run --rm manager-php-cli php bin/phpunit --testsuite=functional

manager-test-functional-coverage:
	docker-compose run --rm manager-php-cli php bin/phpunit --testsuite=functional --coverage-clover var/clover.xml --coverage-html var/coverage

manager-docs:
	docker-compose run --rm manager-php-cli php bin/console api:docs --no-interaction

build:
	docker build --pull --tag=${REGISTRY_ADDRESS}/manager-nginx:${IMAGE_TAG} --file=manager/docker/production/nginx.docker manager
	docker build --pull --tag=${REGISTRY_ADDRESS}/manager-php-fpm:${IMAGE_TAG} --file=manager/docker/production/php-fpm.docker manager
	docker build --pull --tag=${REGISTRY_ADDRESS}/manager-php-cli:${IMAGE_TAG} --file=manager/docker/production/php-cli.docker manager

try-build:
	 REGISTRY_ADDRESS=localhost IMAGE_TAG=0 make build

push: push-manager

push-manager:
	docker push ${REGISTRY_ADDRESS}/manager-nginx:${IMAGE_TAG}
	docker push ${REGISTRY_ADDRESS}/manager-php-fpm:${IMAGE_TAG}
	docker push ${REGISTRY_ADDRESS}/manager-php-cli:${IMAGE_TAG}

deploy:
	ssh -o StrictHostKeyChecking=no deploy@${HOST} -p ${PORT} 'rm -rf manager_${BUILD_NUMBER}'
	ssh -o StrictHostKeyChecking=no deploy@${HOST} -p ${PORT} 'mkdir manager_${BUILD_NUMBER}'
	scp -o StrictHostKeyChecking=no -P ${PORT} docker-compose-prod.yml deploy@${HOST}:manager_${BUILD_NUMBER}/docker-compose.yml
	ssh -o StrictHostKeyChecking=no deploy@${HOST} -p ${PORT} 'cd manager_${BUILD_NUMBER} && echo "COMPOSE_PROJECT_NAME=project-manager" >> .env'
	ssh -o StrictHostKeyChecking=no deploy@${HOST} -p ${PORT} 'cd manager_${BUILD_NUMBER} && echo "REGISTRY_ADDRESS=${REGISTRY_ADDRESS}" >> .env'
	ssh -o StrictHostKeyChecking=no deploy@${HOST} -p ${PORT} 'cd manager_${BUILD_NUMBER} && echo "IMAGE_TAG=${IMAGE_TAG}" >> .env'
	ssh -o StrictHostKeyChecking=no deploy@${HOST} -p ${PORT} 'cd manager_${BUILD_NUMBER} && echo "MANAGER_APP_SECRET=${MANAGER_APP_SECRET}" >> .env'
	ssh -o StrictHostKeyChecking=no deploy@${HOST} -p ${PORT} 'cd manager_${BUILD_NUMBER} && echo "MANAGER_DB_PASSWORD=${MANAGER_DB_PASSWORD}" >> .env'
	ssh -o StrictHostKeyChecking=no deploy@${HOST} -p ${PORT} 'cd manager_${BUILD_NUMBER} && echo "MANAGER_REDIS_PASSWORD=${MANAGER_REDIS_PASSWORD}" >> .env'
	ssh -o StrictHostKeyChecking=no deploy@${HOST} -p ${PORT} 'cd manager_${BUILD_NUMBER} && echo "MANAGER_MAILER_URL=${MANAGER_MAILER_URL}" >> .env'
	ssh -o StrictHostKeyChecking=no deploy@${HOST} -p ${PORT} 'cd manager_${BUILD_NUMBER} && echo "MANAGER_OAUTH_FACEBOOK_SECRET=${MANAGER_OAUTH_FACEBOOK_SECRET}" >> .env'
	ssh -o StrictHostKeyChecking=no deploy@${HOST} -p ${PORT} 'cd manager_${BUILD_NUMBER} && echo "STORAGE_FTP_HOST=${STORAGE_FTP_HOST}" >> .env'
	ssh -o StrictHostKeyChecking=no deploy@${HOST} -p ${PORT} 'cd manager_${BUILD_NUMBER} && echo "STORAGE_FTP_USERNAME=${STORAGE_FTP_USERNAME}" >> .env'
	ssh -o StrictHostKeyChecking=no deploy@${HOST} -p ${PORT} 'cd manager_${BUILD_NUMBER} && echo "STORAGE_FTP_PASSWORD=${STORAGE_FTP_PASSWORD}" >> .env'
	ssh -o StrictHostKeyChecking=no deploy@${HOST} -p ${PORT} 'cd manager_${BUILD_NUMBER} && echo "CENTRIFUGO_WS_HOST=${CENTRIFUGO_WS_HOST}" >> .env'
	ssh -o StrictHostKeyChecking=no deploy@${HOST} -p ${PORT} 'cd manager_${BUILD_NUMBER} && echo "CENTRIFUGO_API_KEY=${CENTRIFUGO_API_KEY}" >> .env'
	ssh -o StrictHostKeyChecking=no deploy@${HOST} -p ${PORT} 'cd manager_${BUILD_NUMBER} && echo "CENTRIFUGO_SECRET=${CENTRIFUGO_SECRET}" >> .env'
	ssh -o StrictHostKeyChecking=no deploy@${HOST} -p ${PORT} 'cd manager_${BUILD_NUMBER} && docker-compose pull'
	ssh -o StrictHostKeyChecking=no deploy@${HOST} -p ${PORT} 'cd manager_${BUILD_NUMBER} && docker-compose up --build manager-progress manager-php-cli'
	ssh -o StrictHostKeyChecking=no deploy@${HOST} -p ${PORT} 'cd manager_${BUILD_NUMBER} && until docker-compose exec -T manager-postgres pg_isready --timeout=0 --dbname=app ; do sleep 1 ; done'
	ssh -o StrictHostKeyChecking=no deploy@${HOST} -p ${PORT} 'cd manager_${BUILD_NUMBER} && docker-compose run --rm manager-php-cli php bin/console doctrine:migrations:migrate --no-interaction'
	ssh -o StrictHostKeyChecking=no deploy@${HOST} -p ${PORT} 'cd manager_${BUILD_NUMBER} && docker-compose --build -d --remove-orphans'
	ssh -o StrictHostKeyChecking=no deploy@${HOST} -p ${PORT} 'rm -f manager'
	ssh -o StrictHostKeyChecking=no deploy@${HOST} -p ${PORT} 'ln -sr manager_${BUILD_NUMBER} manager'

validate-jenkins:
	curl -u ${USER} -XPOST -F "jenkinsfile=<Jenkinsfile" ${HOST}/pipeline-model-converter/validate
