#SELLERMANIA TEST

SYMFONY4  

**Prerequisites:** PHP7, Composer, MySQL

## Getting Started

Clone this project using the following commands:

```
mkdir Sellermania && cd Sellermania
git init
git clone https://github.com/villalonjeremie/sellermania.git
```
Install Symfony CLI
```
curl -sS https://get.symfony.com/cli/installer | bash
```

Install the project dependencies and start the PHP server:
```
composer install
symfony server:start
```

Add Product in Form
```
http://127.0.0.1:8000/product
```

Get List by name of product
```
http://127.0.0.1:8000/list/farcry
```

Test with php-unit
```
php bin/phpunit tests/Entity/ProductTest.php
php bin/phpunit tests/Form/ProductType/ProductTestType.php
```

## Help

You can email villalon.jeremie@gmail.com.