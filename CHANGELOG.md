# Changelog
All notable changes to this project will be documented in this file.

## [0.1.0] - 2019-07-03
### Added
- Menu via KnpMenuBundle
- Tab navigation and grouping a part of menu
- Member
- Group

## [0.0.8] - 2019-06-29
### Added
- Manage of users
  - list users
    - sorting of users
    - filtering of users
  - detail user
  - create user
  - edit user
  - block/active user
  - change role of user
- lib knplabs/knp-paginator-bundle

## [0.0.7] - 2019-06-27
### Added
- Change email in profile
- Auto login after confirm email of register new user
- Attach to facebook in the profile
- Show attached email in facebook profile
- Full name to user profile
- Showing full name instead facebookId and email
- Detach user from social auth 
### Updated
- name classes from Confirm... to SignUpConfirm
### Fixed

## [0.0.6] - 2019-06-24
### Added
- libs "knpuniversity/oauth2-client-bundle", "league/oauth2-facebook", symfony lib encore, "predis/predis"
- auth process via facebook
- theme coreui
- saving session via redis
- console commands and added profile page
- twig widgets status and role
### Updated
- theme into login, signup, reset pages
- theme into base html of view
- UserFetcher (added new methods)
### Fixed
- main menu

## [0.0.5] - 2019-06-23
### Added
- Roles to User domain
- In the User domain initialized the db
- Sign Up and Reset controllers
- Auth controllers
  - Login/logout process
- SingUp, Reset, New Password Forms
- UserRepository, UserFetcher, ResetTokenizerFactory, UserFetcher UserProvider UserIdentity LoginFormAuthenticator
- html.twig pages
- library "finesse/swiftmailer-defaults-plugin"
- library "doctrine/doctrine-fixtures-bundle"
- User admin fixtures
### Updated
- Makefile: load fixtures
- Configured swift mailer  
- Configured security
### Fixed
- architecture of the User domain
- phpunit tests

## [0.0.4] - 2019-06-22
### Added
- User domain model
- PhpUnit tests

## [0.0.3] - 2019-06-22
### Added
- RAW Entities and Repositories
- RAW Controllers

## [0.0.2] - 2019-06-19
### Added
- Docker configuration
- Symfony skeleton

## [0.0.1] - 2019-06-18
### Added
- README.md
- CHANGELOG.md

The format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](http://semver.org/spec/v2.0.0.html).