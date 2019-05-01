<?php

namespace App\Controller;

use App\Repository\OutputRepository;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     * @param OutputRepository $outRepo
     * @param ProductRepository $productRepo
     * @param UserRepository $userRepo
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(OutputRepository $outRepo, ProductRepository $productRepo, UserRepository $userRepo)
    {
        $outputs = $outRepo->findAll();
        $sum = 0;
        $recentOutputs = $outRepo->findBy([], ['outputed_at' => 'DESC'], 20);

        foreach ($outputs as $output){
            $sum += $output->getQuantity() * $output->getProduit()->getPrice();
        }
        return $this->render('home/index.html.twig', [
            'totalSortie' => $sum,
            'outputs' => count($outRepo->findAll()),
            'users' => count($userRepo->findAll()),
            'nbProducts' => count($productRepo->findAll()),
            'recentOutputs' => $recentOutputs
        ]);
    }
}
