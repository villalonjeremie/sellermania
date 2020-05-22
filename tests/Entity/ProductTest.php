<?php

namespace App\Tests\Entity;

use App\Entity\Product;
use PHPUnit\Framework\TestCase;

class ProductTest extends TestCase
{
    public function testProductName()
    {
        $product = new Product();
        $product->setName('Test Name');
        $product->setPrice('20');
        $product->setLimitprice('10');
        $product->setState('neuf');
        $this->assertEquals("Test Name", $product->getName());
        $this->assertEquals("20", $product->getPrice());
    }
}