# silverstripe-simplegtag
A very simple way to add Google Tags to a site for analytics, adwords, etc.

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/DorsetDigital/silverstripe-simplegtag/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/DorsetDigital/silverstripe-simplegtag/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/DorsetDigital/silverstripe-simplegtag/badges/build.png?b=master)](https://scrutinizer-ci.com/g/DorsetDigital/silverstripe-simplegtag/build-status/master)
[![License](https://img.shields.io/badge/License-BSD%203--Clause-blue.svg)](LICENSE.md)
[![Version](http://img.shields.io/packagist/v/dorsetdigital/silverstripe-simplegtag.svg?style=flat)](https://packagist.org/packages/dorsetdigital/silverstripe-simplegtag)

# Requirements
*Silverstripe 4.x


# Installation
* Install the code with `composer require dorsetdigital/silverstripe-simplegtag`
* Run a `dev/build?flush` to update your project

# Usage
This module injects the required code for Google Tag Manager into your pages.
Once installed, set your GTM ID in the site config.

The module injects code into the html `<head>` and the `<noscript>` snippet into the page at the start of the `<body>`

 
## Consent

If you are using the cookie consent module (https://github.com/TheBnl/silverstripe-cookie-consent) then this module will only add the GTM tags if a user has accepted analytics cookies.