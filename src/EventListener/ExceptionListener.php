<?php
namespace App\EventListener;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ExceptionListener
{
    private $session;
    private $router;

    public function __construct(SessionInterface $session, UrlGeneratorInterface $router)
    {
        $this->session = $session;
        $this->router = $router;
    }

    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        // You get the exception object from the received event
        $exception = $event->getException();
//        dd($exception);
        $message = sprintf(
            'Error : %s with code: %s',
            $exception->getMessage(),
            $exception->getCode()
        );
        // Customize your response object to display the exception details
        $response = new Response();
        $response->setContent($message);

        // HttpExceptionInterface is a special type of exception that
        // holds status code and header details
        if ($exception instanceof HttpExceptionInterface) {
            $response->setStatusCode($exception->getStatusCode());
            $response->headers->replace($exception->getHeaders());
        } else {
            $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        // sends the modified response object to the event
        $event->setResponse($response);
        $statusCode = $response->getStatusCode();
        if ($statusCode == 404){
            $event->setResponse(new RedirectResponse('/'));
        }
        if (in_array($statusCode, [403, 402, 500])){
            $session = $event->getRequest()->getSession();
            $session->remove('user');
            $session->remove('token');
            $user = $session->get('user');
            if ($user){
                if (in_array('ROLE_TEACHER', $user->roles)){
                    $url = $this->router->generate('teacher_login');
                    $response = new RedirectResponse($url);
                    $event->setResponse($response);
                }elseif (in_array('ROLE_STUDENT', $user->roles)){
                    $url = $this->router->generate('student_login');
                    $response = new RedirectResponse($url);
                    $event->setResponse($response);
                }
            }
        }
    }
}