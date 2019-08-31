# [PROJECT-MANAGER](https://localhost)

[![version][version-badge]][CHANGELOG]

## Install

### Install composer
```bash
docker-compose run --rm manager-php-cli composer
```

### Install framework
```bash
docker-compose run --rm manager-php-cli composer require slim/slim
```

### Install js libs
```bash
docker-compose run --rm manager-node yarn add -s bootstrap jquery popper.js
```
### Build js/css project
```bash
docker-compose run --rm manager-node npm run dev
```
### Run js/css watcher
```bash
docker-compose run --rm manager-node npm run watch
```
### Production image build
```bash
REGISTRY_ADDRESS=registry IMAGE_TAG=0 make build-production
```

### Before installation

## Documentation

### Exclude autowiring

```yaml
services:
  # makes classes in src/ available to be used as services
  # this creates a service per class whose id is the fully-qualified class name
  App\:
    resource: '../src/*'
    exclude: '../src/{DependencyInjection,Model/User/Entity,Migrations,Tests,Kernel.php}' # exclude Model/User/Entity

  App\Model\User\Entity\User\UserRepository: ~ # include autowiring for Repository
```

### Theme
[coreui/coreui](https://github.com/coreui/coreui-free-bootstrap-admin-template#installation)

## Run tests

### Run functional

### Run unit

## FAQ

### How to config logs
```yaml
vesion: '3'
services:
  manager-php-fpm:
    volumes:
      - manager-php-fpm-logs:/app/var/log

volumes:
  manager-php-fpm-logs:
```

[CHANGELOG]: ./CHANGELOG.md
[version-badge]: https://img.shields.io/badge/version-0.4.0-blue.svg