<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Category;
use AppBundle\Entity\Product;
use AppBundle\Form\CategoryType;
use AppBundle\Form\ProductType;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
        ]);
    }

    /**
     * @Route("/create-dummy-product", name="create-dummy-product")
     */
    public function createDummyProductAction()
    {
        $category = new Category();
        $category->setName('Computer Peripherals');

        $product = new Product();
        $product->setName('Keyboard');
        $product->setPrice(19.99);
        $product->setDescription('Ergonomic and stylish!');

        // relate this product to the category
        $product->setCategory($category);

        $em = $this->getDoctrine()->getManager();
        $em->persist($category);
        $em->persist($product);
        $em->flush();

        return new Response(
            'Saved new product with id: '.$product->getId()
            .' and new category with id: '.$category->getId()
        );
    }

    /**
     * @Route("/create-product", name="create-product")
     */
    public function createProductAction(Request $request)
    {
        $product = new Product();
        $category = new Category();
        $category->addProduct($product);

        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $product = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();

            return $this->redirectToRoute('product-success');
        }

        return $this->render('default/create-product.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/show-product/{productId}", name="show-product", defaults={"productId" = 1})
     *
     * @param $productId
     */
    public function showProductAction($productId)
    {
        $product = $this->getDoctrine()
            ->getRepository('AppBundle:Product')
            ->find($productId);

        return $this->render('default/show-product.html.twig',
            ['product' => $product]
        );
    }

    /**
     * @Route("/show-products-by-category/{categoryId}", name="show-products-by-category", defaults={"categoryId" = 1})
     *
     * @param $categoryId
     */
    public function showProductsByCategoryAction($categoryId)
    {
        $category = $this->getDoctrine()
            ->getRepository('AppBundle:Category')
            ->find($categoryId);

        $products = $category->getProducts();

        return $this->render('default/show-products-by-category.html.twig',
            ['products' => $products]
        );
    }

    /**
     * @Route("/product-success", name="product-success")
     */
    public function productSuccessAction()
    {
        return new Response('<h1>Product erfolgreich angelegt!</h1>');
    }
}
