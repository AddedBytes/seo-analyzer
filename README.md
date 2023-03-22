# SEO Analyzer
Basic PHP library to check several SEO metrics of a website.

[![PHP Composer](https://github.com/AddedBytes/seo-analyzer/actions/workflows/php.yml/badge.svg)](https://github.com/AddedBytes/seo-analyzer/actions/workflows/php.yml)

## This is a fork

This is a fork of [this SEO analyzer project](https://github.com/grgk/seo-analyzer), which appears dead.

## Requirements

* PHP 8.1 (or higher)

## Installation
```
composer require addedbytes/seo-analyzer
```

## How to use?

Please check the [examples](examples) dir

## Available metrics

This tools uses several metrics to analyze and rate website suggesting some SEO improvements:

* https - checks for SSL encryption,
* redirect - checks URL for redirects,
* page_size - analyzes page size,
* load_time - analyzes page load time,
* url_size - analyzes URl size,
* meta - analyzes page meta tags,
* headers - analyzes page HTML headers,
* keyword_density - analyzes keyword density in page content,
* keyword_density_headers - analyzes keyword density in HTML headers on page,
* alt_attributes - analyzes images alt attributes content,
* keyword_url - analyzes URL for specified SEO keyword,
* keyword_path - analyzes URL: path for specified keyword,
* keyword_title - analyzes page title for specified keyword,
* keyword_description - analyzes page meta description for keyword,
* keyword_headers - analyzes HTML headers for keyword,
* keyword_density_keyword - analyzes page content keyword density for specified keyword,
* robots - analyzes "robots.txt" file
* sitemap - analyzes "sitemap.xml" file

## Translations

On how to translate messages please check [analyze_url_with_keyword_translated.php](examples%2Fanalyze_url_with_keyword_translated.php) file.

You can add more translations by adding translation files to [locale](locale) directory.

## License
Licensed under the [MIT license](http://opensource.org/licenses/MIT).
