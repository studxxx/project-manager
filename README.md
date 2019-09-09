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

### How to pass global vars into twig

#### Base HTML
```twig
<!DOCTYPE html>
<html lang="en" class="h-100">
<head>
    <meta charset="UTF-8">
    <meta name="centrifugo-url" content="{{ centrifugo_url }}">
</head>
```
#### Twig config
```yaml
twig:
  # some params
  globals:
    centrifugo_url: '%env(CENTRIFUGO_WS_HOST)%/connection/websocket'
```

#### Get vars in JS
```javascript
const url = document.querySelector('meta[name=centrifugo-url]').getAttribute('content');
```
#### .env file
```
CENTRIFUGO_WS_HOST=ws://localhost:8083
```

[CHANGELOG]: ./CHANGELOG.md
[version-badge]: https://img.shields.io/badge/version-0.5.0-blue.svg