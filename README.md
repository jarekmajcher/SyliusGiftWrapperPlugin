# Sylius Gift Wrapper Plugin

## DEV server

```bash
$ (cd tests/Application && npm install)
$ (cd tests/Application && npm run build)
$ (cd tests/Application && APP_ENV=dev bin/console assets:install public)

$ (cd tests/Application && APP_ENV=dev bin/console doctrine:database:create)
$ (cd tests/Application && APP_ENV=dev bin/console doctrine:schema:create)
$ (cd tests/Application && APP_ENV=dev bin/console doctrine:migrations:sync-metadata-storage)
$ (cd tests/Application && APP_ENV=dev bin/console doctrine:migrations:version --add --all)

$ (cd tests/Application && APP_ENV=dev bin/console sylius:fixtures:load)
$ (cd tests/Application && APP_ENV=dev symfony serve)
```
