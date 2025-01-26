#  [Application Name]

This is a README file for the Atarim url shortner application, a laravel 11 api.

##  Description

The application is an api that has two api endpoints: 'decode' and 'encode' that enables urls to be encoded and decoded. Below explains how the two endpoints work:
* encode:  receives a url, encodes the  url with a shortcode and produces a shortened url based on the api domain. 
* decode: receives a url shortcode, decodes the shortcode and the returns the original url that was associated with that shortcode.

##  Getting Started

The application uses an SQL Lite database, update your `.env` file and add: `DB_CONNECTION=sqlite`.

Connect to the database via a GUI, and access the file: `/database/database.sqlite` to access the application database.

###  Prerequisites
* Http Client - [Postman: https://www.postman.com/] or [Insomnia: https://insomnia.rest/] to create `POST|GET` requests to the api. 
* Database GUI  [TablePlus: https://tableplus.com/] allows you to visually interact with the database.
* PHP 8.0^
* Laravel 11 
* SQL Lite

###  Installation
1.  **Clone the repository:**
`git clone https://github.com/chidozieukaigwe/atarim-url-shortner.git`

2. **Install package dependencies:**
`composer install`

3. **Launch Laravel Server:**
`php artisan serve`
access the api at: http://127.0.0.1:8000/api/v1

### Api Endpoints
* POST - `/api/v1/encode` (expects a payload)
* GET - `/api//v1/decode/{short_code}` (returns a payload)

### Testing 
Use the following commands to run the tests suites associated with this application: 
* Run Unit Tests: `php artisan test --filter=unit`
* Run Feature Tests: `php artisan test --filter=feature`
* Run Full Test Suite: `php artisan test`

## Notes
* This package is used as the decode and encode algorithm:
https://github.com/vinkla/laravel-hashids 
* URLs are persisted via an SQL Lite database
* Api endpoints return JSON