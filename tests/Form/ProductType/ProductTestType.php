<?php

namespace App\Tests\Form\ProductType;

use App\Form\ProductType;
use App\Entity\Product;
use App\Strategy\ProcessPrice;
use App\Strategy\Concurrent;
use App\Strategy\States;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Form\Test\TypeTestCase;

class ProductTestType extends TypeTestCase
{

    public function testSubmitValidData()
    {
        $formData = [
            'name' => 'farcry',
            'limitprice' => '11',
            'state' => 'etat moyen',
            'price' => '20'
        ];

        $product = new Product();

        $form = $this->factory->create(ProductType::class, $product);
        $form->submit($formData);
        $this->assertTrue($form->isSynchronized());
        $setData = Yaml::parseFile(dirname(__FILE__).'/../../../config/testPayloadInput.yaml');

        $processPrice = new ProcessPrice(new Concurrent(), new States());

        foreach ($setData as $data) {
            var_dump($data);
            $price = $processPrice->calculPrice($data);
            $this->assertEquals($data['expected'], $price);
        }



    }
}
