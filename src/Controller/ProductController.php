<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Output;
use App\Entity\Product;
use App\Form\OutputType;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/produits")
 */
class ProductController extends AbstractController
{
    /**
     * @Route("/", name="product_index", methods={"GET"})
     * @param ProductRepository $productRepository
     * @return Response
     */
    public function index(ProductRepository $productRepository): Response
    {
        return $this->render('product/index.html.twig', [
            'products' => $productRepository->findAll(),
        ]);
    }

    /**
     * @Route("/inventaires", name="inventory", methods={"GET"})
     * @param ProductRepository $productRepo
     * @return Response
     */
    public function inventory(ProductRepository $productRepo): Response {
        $products = $productRepo->findAll();
        return $this->render('product/inventory.html.twig', [
            'products' => $products
        ]);
    }

    /**
     * @Route("/sorties", name="outputs", methods={"GET", "POST"})
     * @param Request $request
     * @param ProductRepository $productRepo
     * @return Response
     */
    public function sortieProduit(Request $request, ProductRepository $productRepo): Response
    {
        $output = new Output();
        $form = $this->createForm(OutputType::class, $output);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($output);
            dd($output);
            $entityManager->flush();

            return $this->redirectToRoute('outputs');
        }

        $products = $productRepo->findAll();
        $catRepo = $this->getDoctrine()->getRepository(Category::class);
        $categories = $catRepo->findAll();

        return $this->render('product/outputs.html.twig', [
            'categories' => $categories,
            'products' => $products,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/sorties/ajouter", name="add_outputs", methods={"GET"})
     * @param ProductRepository $productRepo
     * @return Response
     */
    public function ajouterSortieProduit(ProductRepository $productRepo): Response
    {
        $products = $productRepo->findAll();
        return $this->render('product/addOutputs.html.twig', [
            'products' => $products
        ]);
    }

    /**
     * @Route("/nouveau", name="product_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($product);
            $entityManager->flush();

            return $this->redirectToRoute('product_index');
        }

        return $this->render('product/new.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="product_show", methods={"GET"})
     * @param Product $product
     * @return Response
     */
    public function show(Product $product): Response
    {
        return $this->render('product/show.html.twig', [
            'product' => $product,
        ]);
    }

    /**
     * @Route("/{id}/editer", name="product_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Product $product
     * @return Response
     */
    public function edit(Request $request, Product $product): Response
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('product_index', [
                'id' => $product->getId(),
            ]);
        }

        return $this->render('product/edit.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="product_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Product $product): Response
    {
        if ($this->isCsrfTokenValid('delete'.$product->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($product);
            $entityManager->flush();
        }

        return $this->redirectToRoute('product_index');
    }
}
