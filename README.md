# PHPFastCGI Silex Adapter

[![Latest Stable Version](https://poser.pugx.org/phpfastcgi/silex-adapter/v/stable)](https://packagist.org/packages/phpfastcgi/silex-adapter)
[![Build Status](https://travis-ci.org/PHPFastCGI/SilexAdapter.svg?branch=master)](https://travis-ci.org/PHPFastCGI/SilexAdapter)
[![Coverage Status](https://coveralls.io/repos/PHPFastCGI/SilexAdapter/badge.svg?branch=master&service=github)](https://coveralls.io/github/PHPFastCGI/SilexAdapter?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/PHPFastCGI/SilexAdapter/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/PHPFastCGI/SilexAdapter/?branch=master)
[![Total Downloads](https://poser.pugx.org/phpfastcgi/silex-adapter/downloads)](https://packagist.org/packages/phpfastcgi/silex-adapter)

A PHP package which allows Silex applications to reduce overheads by exposing their Request-Response structure to a FastCGI daemon.

Visit the [project website](http://phpfastcgi.github.io/).

## Introduction

Using this adapter, Silex applications can stay alive between HTTP requests whilst operating behind the protection of a FastCGI enabled web server.

## Current Status

This project is currently in early stages of development and not considered stable. Importantly, this library currently lacks support for uploaded files.

Contributions and suggestions are welcome.

## Installing

```sh
composer require "phpfastcgi/silex-adapter:^0.5"
```

## Usage

```php
<?php // web/command.php

// Include the composer autoloader
require_once dirname(__FILE__) . '/../vendor/autoload.php';

use PHPFastCGI\FastCGIDaemon\ApplicationFactory;
use PHPFastCGI\Adapter\Silex\ApplicationWrapper;
use Silex\Application;

// Create your Silex application
$app = new Application;
$app->get('/hello/{name}', function ($name) use ($app) {
    return 'Hello ' . $app->escape($name);
});

// Create the kernel for the FastCGIDaemon library (from the Silex application)
$kernel = new ApplicationWrapper($app);

// Create the symfony console application
$consoleApplication = (new ApplicationFactory)->createApplication($kernel);

// Run the symfony console application
$consoleApplication->run();
```

If you wish to configure your FastCGI application to work with the apache web server, you can use the apache FastCGI module to process manage your application.

This can be done by creating a FastCGI script that launches your application and inserting a FastCgiServer directive into your virtual host configuration.

```sh
#!/bin/bash
php /path/to/silex/web/command.php run
```

```
FastCgiServer /path/to/silex/web/script.fcgi
```

By default, the daemon will listen on FCGI_LISTENSOCK_FILENO, but it can also be configured to listen on a TCP address. For example:

```sh
php /path/to/command.php run --port=5000 --host=localhost
```

If you are using a web server such as NGINX, you will need to use a process manager to monitor and run your application.
