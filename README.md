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

### Theme
[coreui/coreui](https://github.com/coreui/coreui-free-bootstrap-admin-template#installation)

## Run tests

### Run functional

### Run unit

## FAQ

[CHANGELOG]: ./CHANGELOG.md
[version-badge]: https://img.shields.io/badge/version-0.0.6-blue.svg