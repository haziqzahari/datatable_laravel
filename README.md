## About DataTable for Laravel

DataTables is library provided by DataTables - SpryMedia Ltd. focused on displaying datatable equipped with powerful features such as pagination, search and etc. However, insufficient documentations and manuals on implementing on server-side processing especially using Laravel. Here, I have created some useful examples to help with using DataTable in Laravel.

## How To Use

1. Clone this repository to your local repository.
2. You will find DataTable folder under /app. This folder contains DataTableInterface and DataTableTraits. These two files are the essentials.
3. Configure your .env to match your local environment and database setup. Note : This release is only for MySQL.
4. Run composer install
5. Run npm install && npm run dev
6. Run php artisan:migrate --seed
7. You are good to go!

Note : This release is only for showing the example of implementation.

## Architecture Explaination

1. The DataTable is connected to server - side through the use of AJAX. 
2. The AJAX is set up to be pointed to the API.
3. The API routing is redirected to invoke the DataTableController::class. Note : You can set up all DataTables here.
4. All request parameters is configured as shown in DataTableController::class.
5. With the use of DataTableInterface, you can create your own class and implement DataTableInterface and use DataTableTraits.
6. You can override the methods available in the interface as per instructed in the code.

## Contributing

Any contributions to ideas, works and pull requests are welcomed warmly ^.^ 

## Error & Bug Reporting

If you discover any bugs or errors within this work, please send an e-mail to Haziq Zahari via [haziqzahari98@outlook.com](mailto:haziqzahari98@outlook.com). All errors and bugs will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
