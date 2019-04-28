<?php

namespace App\Controller;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends Controller
{
    private $client;

    public function __construct()
    {
        $this->client = new Client(['base_uri'=> getenv('BASE_URL'), 'timeout' => 2.0]);
    }

    //La page d'acceuil de l'application
    /**
     * @Route(path="/", name="home")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function home(){
        return $this->render('default/index.html.twig');
    }
}
