#ComparisonUtility
[![Build Status](https://travis-ci.org/paslandau/ComparisonUtility.svg?branch=master)](https://travis-ci.org/paslandau/ComparisonUtility)

Collection of different common comparison classes/functions

##Description
[todo]

##Requirements

- PHP >= 5.5

##Installation

The recommended way to install ComparisonUtility is through [Composer](http://getcomposer.org/).

    curl -sS https://getcomposer.org/installer | php

Next, update your project's composer.json file to include ComparisonUtility:

    {
        "repositories": [
            {
                "type": "git",
                "url": "https://github.com/paslandau/ComparisonUtility.git"
            }
        ],
        "require": {
             "paslandau/ComparisonUtility": "~0"
        }
    }

After installing, you need to require Composer's autoloader:
```php

require 'vendor/autoload.php';
```