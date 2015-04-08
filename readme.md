## My Finance

This is a small application that can help you import all your financial transactions from different sources and help you create different groups
in order to group your transactions and in the end to produce a monthly report that will show your expenses or income as well.

Please keep in mind that this is still in beta version.

## Requirements

PHP 5.5 and above

## Installation

git clone git@github.com:andreas22/my-finance.git

cd my-finance

composer update

php -S localhost:8000 -t public/

and last, open your browser at http://localhost:8000 and have fun :)


## Demo

go to new file

upload sample-file.csv which can be found in the root of the clone application

go to categories

and create 2 categories as follows:

name: atm  / labels: atm
name: supermarket / labels: supermarket

go to my files

click [view report] next to the uploaded file


## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](http://laravel.com/docs/contributions).

### License

My finance is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)
