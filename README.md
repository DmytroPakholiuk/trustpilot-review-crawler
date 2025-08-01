<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

# Trustpilot review scraper

## Installation

You will need:

- Linux (didn't test it on Windows)
- PHP 8.2 or higher
- PHP extensions (PDO SQLite, mbstring, xml, json etc)
- SQLite installed

Steps to install:

- run composer install
- cp .env.example .env
- copy database.sqlite from the root of the projet to "database" direcory or run "php artisan migrate"
- copy "images" directory to storage/app/private/images (optional, gets autofixed on command run)

## Configuration

There are 3 things to configure: URL list, ALL_LANGUAGES and QUICK_SEARCH

- URL list is in the "config/review_populator/trustpilot.csv". Make sure to have a correct CSV there
- ALL_LANGUAGES in .env file (default FALSE) defines whether it will add "?languages=all" to the URLs, pulling many more reviews
- QUICK_SEARCH in .env file (default TRUE) defines whether the program will stop scanning product page after the new reviews have been depleted. Good to speed up recurrent checks for new reviews, but will not check the older reviews for avatar and profile changes

## Running

Execute php artisan app:scrape-reviews

## Images

Images are stored in storage/app/private/images . Each image is in its own directory, named as its ID in database.
The "images" directory in the root of the project is just a COPY of the results of running the command. 

## Logging

Logs are recorded to storage/logs/laravel.log . Also, if the code is executed through CLI, it will output execution logs there too.
