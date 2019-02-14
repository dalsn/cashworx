# Cashworx

[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Total Downloads](https://img.shields.io/packagist/dt/league/skeleton.svg?style=flat-square)](https://packagist.org/packages/dalsn/cashworx)

## Install

Via Composer

``` bash
$ composer require dalsn/cashworx
```

## Usage

``` php
$cashworx = new \Dalsn\Cashworx\Cashworx($access_key, $access_secret, $apiUrl);

Create Invoice
$cashworx->createInvoice($invoice_data);

Get Invoice
$cashworx->getInvoice($invoice_number);

Get all Invoices
$cashworx->getInvoices();

Confirm Payment
$cashworx->getPayment($invoice_number);
```

## Contributing

Please see [CONTRIBUTING](https://github.com/dalsn/cashworx/blob/master/CONTRIBUTING.md) for details.

## Credits

- [Dalhatu Njidda](https://github.com/dalsn)
- [All Contributors](https://github.com/dalsn/cashworx/contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
