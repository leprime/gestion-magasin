<?php

namespace App\Controller;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Validator\Constraints\Length;

/**
 * Class SecurityController
 * @package App\Controller
 */
class SecurityController extends Controller
{
    private $client;
    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->client = new Client(['base_uri'=> getenv('BASE_URL')]);
        $this->session = $session;

    }

    /**
     * @Route(path="/enseignant/connexion", name="teacher_login")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function login(Request $request)
    {
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
                $rep = $this->client->post('/api/enseignant/login', ['form_params'=>$data, 'headers'=>$headers]);
            }catch (RequestException $e){
                dd($e);
            }
            $result = json_decode($rep->getBody()->getContents());
            if (!$result->success){
                $this->addFlash('error', $result->error);
            }
            if ($rep->getStatusCode() == 200 && $result->success){
                $this->session->set('token', $result->token);
                return $this->redirectToRoute('index_teacher');
            }
        }
        return $this->render('teacher/login.html.twig', [
            'form'=>$form->createView(),
        ]);
    }

    /**
     * @Route("/logout", name="logout")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function logout(Request $request)
    {
        $user = $request->getSession()->get('user');
        $request->getSession()->remove('user');
        $request->getSession()->remove('token');
        if ($user && in_array('ROLE_TEACHER', $user->roles)){
            return $this->redirectToRoute('teacher_login');
        }
        return $this->redirectToRoute('student_login');
    }
    

    /**
     * @Route(path="/etudiant/verification", name="check_student", options={"expose":true})
     * @Method({"GET", "POST"})
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function check_registration(Request $request) {
        $form = $this->createFormBuilder([])
            ->add('username', TextType::class, [
                'label'=>"Nom d'utilisateur",
                'required' => true,
                'constraints' => array(new Length(array('min' => 6))),
                'attr'=>['class'=>'form-group form-control'],
                'data' => $this->session->get('firstStudent')->username
            ])->add('password', RepeatedType::class, array(
                'type' => PasswordType::class,
                'constraints' => array(new Length(array('min' => 8, 'minMessage' => 'Mot de passe trop court (minimum 8 caractères)'))),
                'invalid_message' => 'Les champs de mot de passe doivent correspondre',
                'options' => array('attr' => array('class' => 'form-group form-control')),
                'required' => true,
                'first_options'  => array('label' => 'Nouveau mot de passe'),
                'second_options' => array('label' => 'Confirmation de mot de passe'),
            ))
            ->getForm()
        ;
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $credentials = $form->getData();
            $user = $this->session->get('firstStudent');
            try{
                $req = $this->client->post('/api/daas_student/'.$user->id.'/complete', [
                    'json' => $credentials
                ]);
                $response = json_decode($req->getBody()->getContents());
                dd($response);
                //Redirect to login
                $this->session->set('firstStudent', $response->student);
                return $this->redirectToRoute('student_login');
            }catch (RequestException $exception){
                if ($exception->hasResponse()) {
                    $errors = json_decode($exception->getResponse()->getBody()->getContents());
                    $this->addFlash('error', str_replace("numCarte:", "", $errors->detail));
//                    $this->addFlash('error', str_replace("numCarte:", "", $errors->detail));
                }
            }

        }
        return $this->render('student/validate.html.twig', ['form' => $form->createView()]);
    }
}
