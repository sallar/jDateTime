UNMAINTAINED
===
:warning: This package is deprecated and unmaintained. Please **DO NOT** use it in production unless it’s absolutely necessary and at your own risk. There are several other modern Shamsi/Jalali date packages out there like [this one](http://farhadi.ir/blog/1389/02/10/persian-calendar-for-php-53/) which you can use.

:round_pushpin: If you happen to be a php developer and want to maintain this project, please give me a shout so I can give you write access to this project.

# jDateTime

PHP class to convert dates from Gregorian calendar system to Jalali calendar system and vice versa. Supports dates beyond 2038.  
Jalali, also known as Shamsi or Hijri Shamsi is the Iranian calendar system.  
[![Build Status](https://travis-ci.org/sallar/jDateTime.png?branch=master)](https://travis-ci.org/sallar/jDateTime)

# About v2.2.0

PHP's default `date` function does not support years higher than 2038, so the `DateTime` class was introduced in PHP5 to solve this problem and provide more sophisticated date methods. Iranian users have been using an old `jdate` function to convert Gregorian date to the Jalali equivalent, which is completely based on the old php `date` function so its pretty much out-dated. 

# Requirements

jDateTime Requires **PHP >= 5.2**  

# Installation

## Using Composer

You can install this package using [composer](https://getcomposer.org). Add this package to your `composer.json`:  

```
"require": {
	"sallar/jdatetime": "dev-master"
}
```

or if you prefer command line, change directory to project root and:

```
php composer.phar require "sallar/jdatetime":"dev-master"
```

## Manual Installation

Get a copy of package source code. You can do this in two ways:

1. Download ZIP version of the source code and unzip it in desired location  
2. Run `git clone https://github.com/sallar/jDateTime.git` to clone this repository  

After getting a copy of source code, it is enough to include `jdatetime.class.php` where you need to use it.

```php
require_once 'path/to/source/jdatetime.class.php';
```

# Examples

Please see [examples.php](examples.php) and [example-static.php](examples-static.php) for working examples.

# Contributors:
- [Sallar Kaboli](http://sallar.me)  
- [Omid Pilevar](http://pilevar.ir)
- [Afshin Mehrabani](http://afshinm.name)  
- [Amir Latifi](http://amiir.me)
- [Ruhollah Namjoo](https://github.com/namjoo)

## License
jDateTime was created by [Sallar Kaboli](http://sallar.me) and released under the [MIT License](http://opensource.org/licenses/mit-license.php).

Copyright (C) 2016 [Sallar Kaboli](http://sallar.me)  
  
Original Jalali to Gregorian (and vice versa) convertor:  
Copyright (C) 2000  Roozbeh Pournader and Mohammad Toossi

    The MIT License (MIT)
    
    Copyright (C) 2003-2016 Sallar Kaboli

    Permission is hereby granted, free of charge, to any person obtaining a
    copy of this software and associated documentation files (the "Software"),
    to deal in the Software without restriction, including without limitation
    the rights to use, copy, modify, merge, publish, distribute, sublicense,
    and/or sell copies of the Software, and to permit persons to whom the
    Software is furnished to do so, subject to the following conditions:

    1- The above copyright notice and this permission notice shall be included
    in all copies or substantial portions of the Software.
    
    2- THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS
    OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
    FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
    AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
    LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
    FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER
    DEALINGS IN THE SOFTWARE.

# Resources
- [List of supported timezones](http://www.php.net/manual/en/timezones.php)  
- [Documentation and Instructions in Persian](http://sallar.me/farsi/projects/jdatetime/)  
