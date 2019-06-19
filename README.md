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

### Production image build
```bash
REGISTRY_ADDRESS=registry IMAGE_TAG=0 make build-production
```

### Before installation

## Documentation

## Run tests

### Run functional

### Run unit

## FAQ

[CHANGELOG]: ./CHANGELOG.md
[version-badge]: https://img.shields.io/badge/version-0.0.2-blue.svg