#comparison-utility
[![Build Status](https://travis-ci.org/paslandau/comparison-utility.svg?branch=master)](https://travis-ci.org/paslandau/comparison-utility)

Collection of different common comparison classes/functions

##Description
[todo]

##Requirements

- PHP >= 5.5

##Installation

The recommended way to install comparison-utility is through [Composer](http://getcomposer.org/).

    curl -sS https://getcomposer.org/installer | php

Next, update your project's composer.json file to include ComparisonUtility:

    {
        "repositories": [
            {
                "type": "git",
                "url": "https://github.com/paslandau/comparison-utility.git"
            }
        ],
        "require": {
             "paslandau/comparison-utility": "~0"
        }
    }

After installing, you need to require Composer's autoloader:
```php

require 'vendor/autoload.php';
```