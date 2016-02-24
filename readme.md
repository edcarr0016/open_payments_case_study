##README

This readme assumes that you have Composer, Node.js, and Java installed. If not, they can be obtained from the following locations: 
  * [Composer](https://getcomposer.org/download/)
  * [Node.js](https://nodejs.org/en/download/)
  * [Java](https://www.java.com/en/download/)

1. Once you have cloned this repository, `cd` into the base directory.
2. Run `composer install`
3. Run `npm install`
4. Run `bower install`
5. Run `gulp`
6. Download **Elasticsearch** from [here](https://www.elastic.co/downloads/elasticsearch) and unpack zip file
7. Start on
  + Linux: run `bin/elasticsearch`
  + Windows: run `bin/elasticsearch.bat` 
7. Run `php artisan migrate`
8. Run `php artisan db:seed --class=OpenPaymentsSeeder`
9. Linux Env Only: execute the following `* * * * * php /path/to/artisan schedule:run >> /dev/null 2>&1`
10. In the `.env` file in your base directory, create or change the `BASE_DIR` paramater to match your machine's base directory
11. Run `php artisan serve`

Thank you for stopping by! 


