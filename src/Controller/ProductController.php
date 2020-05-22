<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Product;
use App\Form\ProductType;
use App\Strategy\ProcessPrice;
use App\Strategy\Concurrent;

class ProductController extends AbstractController
{
    protected $processPrice;
    protected $concurrent;

    public function __construct(ProcessPrice $processPrice, Concurrent $concurrent)
    {
        $this->processPrice = $processPrice;
        $this->concurrent = $concurrent;
    }

    /**
     * @Route("/product", name="product")
     */
    public function index(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $parameters = $request->request->get('product');

        var_dump($parameters);

die;
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $productExist = $em->getRepository(Product::class)->findOneByName($parameters['name']);
            $price = $this->processPrice->calculPrice($parameters);

            if ($productExist) {
                $productExist->setPrice($price);
                $productExist->setState($parameters['state']);
                $productExist->setLimitprice($parameters['limitprice']);
            } else {
                $product->setPrice($price);
                $em->persist($product);
            }

            $em->flush();
            $this->addFlash("success", "Produit ajouté ou mis à jour");
        }

        return $this->render('product/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/list/{slug}", name="list")
     */
    public function list(string $slug)
    {
        $em = $this->getDoctrine()->getManager();
        $productObject = $em->getRepository(Product::class)->findOneByName($slug);

        $listConcurrent = $this->concurrent->getArrayConcurrent($slug);

        array_multisort(
            array_column($listConcurrent, 'price'),
            SORT_ASC,
            $listConcurrent
        );

        $product = [
            'price' => $productObject->getPrice(),
            'vendor' => 'Mon Magasin',
            'state' => $productObject->getState()
        ];

        return $this->render('product/list.html.twig', [
            'listConcurrents' => $listConcurrent,
            'product' => $product
        ]);
    }
}
