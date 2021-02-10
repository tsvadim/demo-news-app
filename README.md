<p align="center">
    <a href="https://github.com/yiisoft" target="_blank">
        <img src="https://avatars0.githubusercontent.com/u/993323" height="100px">
    </a>
    <h1 align="center">Yii 2 Advanced Template with AdminLTE and RBAC</h1>
    <br>
</p>

Yii 2 Advanced Project Template is a skeleton [Yii 2](http://www.yiiframework.com/) application best for
developing complex Web applications with multiple tiers.

The template includes three tiers: front end, back end, and console, each of which
is a separate Yii application.

The template is designed to work in a team development environment. It supports
deploying the application in different environments.

Documentation is at [docs/guide/README.md](docs/guide/README.md).

INSTALLATION
------------

### Install via vagrant (Установка при помощи vagrant)

```vagrant up```

### Install via Composer (Установка при помощи Composer)

Если у вас нет [Composer](http://getcomposer.org/), вы можете скачать его и установить следуя инструкции
[getcomposer.org](http://getcomposer.org/doc/00-intro.md#installation-nix).
On Linux and Mac OS X, you'll run the following commands:

```curl -sS https://getcomposer.org/installer | php```

If you need global composer, use the following command (Если вам нужен глобальный composer, используйте следующую команду):
 
```mv composer.phar /usr/local/bin/composer```


If composer has global installation, use (Если composer установлен глобально, используйте):

```php composer```

If composer installed only for this project, use (Если composer установлен только для этого проекта, используйте):

```php composer.phar```


You can then install the application using the following commands (После этого вы можете установить приложение, используя следующие команды):

``` php composer.phar global require "fxp/composer-asset-plugin:~1.1.1" ```

``` php composer.phar update ```

###Configuration application (Настройка приложения)

Выберем конфигурацию приложения Development (Разреботка) или Production (Продакшн):

``` php init ```

Выполним настройку приложения:

####Внимание! Если возникли трудности с кодировкой, попробовать команду "chcp 65001" если не помогло, переключится на английский язык в файле конфинурации

```php yii start```

После успешной настройки приложения, установщик предложит вам создать Базу Данных, для этого выполните команду:

```php yii migrate/up```

Создаём администратора.

```php yii user/admin```

DIRECTORY STRUCTURE
-------------------

```
common
    config/              contains shared configurations
    mail/                contains view files for e-mails
    models/              contains model classes used in both backend and frontend
    tests/               contains tests for common classes    
console
    config/              contains console configurations
    controllers/         contains console controllers (commands)
    migrations/          contains database migrations
    models/              contains console-specific model classes
    runtime/             contains files generated during runtime
backend
    assets/              contains application assets such as JavaScript and CSS
    config/              contains backend configurations
    controllers/         contains Web controller classes
    models/              contains backend-specific model classes
    runtime/             contains files generated during runtime
    tests/               contains tests for backend application    
    views/               contains view files for the Web application
    web/                 contains the entry script and Web resources
frontend
    assets/              contains application assets such as JavaScript and CSS
    config/              contains frontend configurations
    controllers/         contains Web controller classes
    models/              contains frontend-specific model classes
    runtime/             contains files generated during runtime
    tests/               contains tests for frontend application
    views/               contains view files for the Web application
    web/                 contains the entry script and Web resources
    widgets/             contains frontend widgets
vendor/                  contains dependent 3rd-party packages
environments/            contains environment-based overrides
```
