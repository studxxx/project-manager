# Changelog
All notable changes to this project will be documented in this file.

## [0.11.0] - 2021-02-17
### Added
- playbook

## [0.10.0] - 2021-02-17
### Updated
- framework to 4.4
- php to 7.4

## [0.9.0] - 2021-02-16
### Added
- jenkins-ci

## [0.8.2] - 2021-02-09
### Updated
- npm libraries

## [0.8.1] - 2019-02-09
### Fixed
- init project

## [0.8.0] - 2019-10-01
### Added
- Some improvements

## [0.7.0] - 2019-09-22
### Added
- OAuth server
- API sign up
- API projects controller
- API tasks controller

## [0.6.1] - 2019-09-10
### Update
- Changed session to redis extension
- Updated vendor

## [0.6.0] - 2019-09-10
### Added
- domain events
- queue on the redis
- symfony/messegner
- centrifugo

## [0.5.0] - 2019-09-08
### Added
- history changes
- tasks feed

## [0.4.1] - 2019-09-01
### Updated
- doctrine config
  - ignoring of sequence in migrations
- optional arguments in the task model
### Fixed
- production mailer url

## [0.4.0] - 2019-08-31
### Added
- File storage
- Calendar
- Comments
### Updated
- libs via composer

## [0.3.4] - 2019-08-07
### Updated
- Config log for production
### Removed
- ProcessorCompilerPass


## [0.3.2] - 2019-08-07
### Added
- text decorators
  - MarkdownExtension
  - purifier bundle
  - WorkProcessor

## [0.3.1] - 2019-08-04
### Fixed
- Changelog

## [0.3.0] - 2019-08-04
### Added
- Tasks

## [0.2.1] - 2019-07-20
### Fixed
- Unit tests

## [0.2.0] - 2019-07-20
### Added
- Project entity
- Department entity
- Role entity
- Membership entity

## [0.1.1] - 2019-07-03
### Added
- references to MemberFixture
- requirement to route work.members.show
### Updated
- fixtures
- log type error to warning
- composer libs

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