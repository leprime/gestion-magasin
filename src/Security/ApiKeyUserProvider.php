<?php
namespace App\Security;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\User;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;

class ApiKeyUserProvider implements UserProviderInterface
{
    private $user;
    private $client;
    private $session;
    private $router;

    public function __construct(SessionInterface $session, UrlGeneratorInterface $router)
    {
        $this->client = new Client(['base_uri'=> getenv('BASE_URL')]);
        $this->session = $session;
        $this->router = $router;
    }

    public function getUsernameForApiKey($token)
    {
        try{
            $request = $this->client->get('/api/me', [
                'headers' => ['Authorization' => 'Bearer '.$token]
            ]);
            $response = json_decode($request->getBody());
            $this->session->set('user', $response->user);
            // Look up the username based on the token in the database, via
            // an API call, or do something entirely different
            $username = $response->user->username;
            return $username;
        }catch (RequestException $exception){
            if ($exception->getCode() === 401){
                $this->session->remove('token');
                if (in_array('ROLE_TEACHER', $this->session->get('user')->roles)){
                    $url = $this->router->generate('teacher_login');
                    $this->session->remove('user');
                    return new RedirectResponse($url);
                }elseif (in_array('ROLE_STUDENT', $this->session->get('user')->roles)){
                    $url = $this->router->generate('student_login');
                    $this->session->remove('user');
                    return new RedirectResponse($url);
                }
            }
        }
    }

    public function loadUserByUsername($username)
    {
        $user = $this->session->get('user');
        if(!$user){
            return new RedirectResponse($this->router->generate('home'));
        }
        return new User($username, $user->password, $user->roles);
    }

    public function refreshUser(UserInterface $user)
    {
        // this is used for storing authentication in the session
        // but in this example, the token is sent in each request,
        // so authentication can be stateless. Throwing this exception
        // is proper to make things stateless
        throw new UnsupportedUserException();
    }

    public function supportsClass($class)
    {
        return User::class === $class;
    }
}
