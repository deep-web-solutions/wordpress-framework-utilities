# DWS WordPress Framework - Utilities

**Contributors:** Antonius Hegyes, Deep Web Solutions GmbH  
**Requires at least:** 5.5  
**Tested up to:** 5.9  
**Requires PHP:** 7.4  
**Stable tag:** 1.0.0  
**License:** GPLv3 or later  
**License URI:** http://www.gnu.org/licenses/gpl-3.0.html  


## Description

[![GPLv3 License](https://img.shields.io/badge/License-GPL%20v3-yellow.svg)](https://opensource.org/licenses/)
[![PHP Syntax Errors](https://github.com/deep-web-solutions/wordpress-framework-utilities/actions/workflows/php-syntax-errors.yml/badge.svg)](https://github.com/deep-web-solutions/wordpress-framework-utilities/actions/workflows/php-syntax-errors.yml)
[![WordPress Coding Standards](https://github.com/deep-web-solutions/wordpress-framework-utilities/actions/workflows/wordpress-coding-standards.yml/badge.svg)](https://github.com/deep-web-solutions/wordpress-framework-utilities/actions/workflows/wordpress-coding-standards.yml)
[![Codeception Tests](https://github.com/deep-web-solutions/wordpress-framework-utilities/actions/workflows/codeception-tests.yml/badge.svg?branch=master)](https://github.com/deep-web-solutions/wordpress-framework-utilities/actions/workflows/codeception-tests.yml)
[![Maintainability](https://api.codeclimate.com/v1/badges/ccac5a84720a1b6230f6/maintainability)](https://codeclimate.com/github/deep-web-solutions/wordpress-framework-utilities/maintainability)

A set of related utility classes to kick-start WordPress development. This package contains a series of services and handlers
as defined by [our foundations package](https://github.com/deep-web-solutions/wordpress-framework-foundations).


## Documentation

Documentation for this module and the rest of the DWS WP Framework can be found [here](https://framework.deep-web-solutions.com/utilities-module/motivation-and-how-to-use).


## Installation

The package is designed to be installed via Composer. It may work as a stand-alone but that is not officially supported.
The package's name is `deep-web-solutions/wp-framework-utilities`.

If the package will be used outside a composer-based installation, e.g. inside a regular WP plugin, you should install
using the `--ignore-platform-reqs` option. If you don't do that, the bundled `DWS WordPress Framework - Bootstrapper` package
will only be able to perform checks for the WordPress version because composer will throw an error in case of an incompatible PHP version.

## Contributing

Contributions both in the form of bug-reports and pull requests are more than welcome!


## Frequently Asked Questions

- Will you support earlier versions of WordPress and PHP?

Unfortunately not. PHP 7.3 is close to EOL (March 2021), and we consider 7.4 to provide a few features that are absolutely amazing.
Moreover, WP 5.5 introduced a few new features that we really want to use as well, and we consider it to be one of the first versions
of WordPress to have packed a more-or-less mature version of Gutenberg.

If you're using older versions of either one, you should really consider upgrading at least for security reasons.

- Is this bug-free?

Hopefully yes, probably not. If you found any problems, please raise an issue on Github!


## Changelog

### 1.0.0 (TBD) 
* First official release.