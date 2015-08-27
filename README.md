# Speedex

![GitHub License](https://img.shields.io/badge/license-GPLv2-blue.svg)
[![Build Status](https://travis-ci.org/PHPFastCGI/Speedex.svg?branch=master)](https://travis-ci.org/PHPFastCGI/Speedex)
[![Coverage Status](https://coveralls.io/repos/PHPFastCGI/Speedex/badge.svg?branch=master)](https://coveralls.io/r/PHPFastCGI/Speedex?branch=master)

A PHP package which allows Silex applications to reduce overheads by exposing their Request-Response structure to a FastCGI daemon.

Visit the [project website](http://phpfastcgi.github.io/).

## Introduction

Using this package, Silex applications can stay alive between HTTP requests whilst operating behind the protection of a FastCGI enabled web server.

## Installing

```sh
composer require "phpfastcgi/speedex:0.3.*"
```

## Usage

```php
<?php // web/command.php

// Include the composer autoloader
require_once dirname(__FILE__) . '/../vendor/autoload.php';

use PHPFastCGI\FastCGIDaemon\ApplicationFactory;
use PHPFastCGI\Speedex\ApplicationWrapper;
use Silex\Application as SilexApplication;

// Create your Silex application
$app = new SilexApplication;
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

If you are using a web server such as nginx, you will need to use a process manager to monitor and run your application.

## Current Status

This library is currently in early development stages and not considered stable. A stable release is expected by September 2015.

Contributions and suggestions are welcome
