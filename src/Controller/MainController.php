<?php

namespace App\Controller;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends Controller
{
    private $client;
    private $request;
    private $session;

    public function __construct(RequestStack $requestStack, SessionInterface $session)
    {
        $this->client = new Client(['base_uri'=> getenv('BASE_URL')]);
        $this->request = $requestStack->getCurrentRequest();
        $this->session = $session;
    }

    /**
     * @Route(path="/etudiant/connexion", name="student_login")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function login(Request $request)
    {
        if ($this->getUser()){
            return $this->redirectToRoute('index_student');
        }
        $form = $this->createFormBuilder([])
            ->add('username', TextType::class, [
                'label'=>"Nom d'utilisateur",
                'attr'=>['class'=>'form-group form-control']
            ])
            ->add('password', PasswordType::class, [
                'label'=>"Mot de passe",
                'attr'=>['class'=>'form-group form-control']
            ])
            ->getForm()
        ;
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $data = $form->getData();
            $headers = ['Content-Type'=>'application/x-www-form-urlencoded'];
            try{
                $rep = $this->client->post('/api/student/login', ['form_params'=>$data, 'headers'=>$headers]);
                $result = json_decode($rep->getBody()->getContents());
//                dd($result);
                if (!$result->success){
                    $this->addFlash('error', $result->error);
                }
                if ($rep->getStatusCode() == 200 && $result->success){
                    $this->session->set('token', $result->token);
                    return $this->redirectToRoute('index_student');
                }
            }catch (RequestException $e){
                if ($e->hasResponse() && in_array($e->getCode(), [500, 405])){
                    $this->addFlash('error', 'Erreur du serveur, veuillez réessayer plus tard');
//                    dd($e->getResponse()->getBody()->getContents());
                }
            }
        }
        return $this->render('main_controller/login.html.twig', [
            'form'=>$form->createView(),
        ]);
    }
    
    public function recovery(){
        return $this->render('main_controller/recovery.html.twig');
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * throws \GuzzleHttp\Exception\GuzzleException
     */
    public function evaluateAction(){
        return $this->render('student/evaluated_ues.html.twig');
    }

    /**
     * @Route(path="/etudiant/messages", name="student_message")
     */
    public function studentMessage(){
        return $this->render('main_controller/messages.html.twig');
    }

    /**
     * @Route(path="/enseignant/messages", path="teacher_message")
     */
    public function teacherMessage(){
        return $this->render('main_controller/messages.html.twig');
    }
}
