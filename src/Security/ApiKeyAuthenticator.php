<?php
namespace App\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\PreAuthenticatedToken;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;
use Symfony\Component\Security\Http\Authentication\SimplePreAuthenticatorInterface;

class ApiKeyAuthenticator extends Controller implements SimplePreAuthenticatorInterface, AuthenticationFailureHandlerInterface
{
    private $session;
    private $router;
    private $request;
    public function __construct(SessionInterface $session,  UrlGeneratorInterface $router, RequestStack $request)
    {
        $this->session = $session;
        $this->router = $router;
        $this->request = $request->getCurrentRequest();
    }

    public function createToken(Request $request, $providerKey)
    {
        $apiKey = $this->session->get('token');
        // or if you want to use an "apikey" header, then do something like this:
        if (!$apiKey) {
            throw new BadCredentialsException();
            // or to just skip api key authentication
            // return null;
        }
//        dd($providerKey);
        return new PreAuthenticatedToken(
            'anon.',
            $apiKey,
            $providerKey
        );
    }

    public function supportsToken(TokenInterface $token, $providerKey)
    {
        return $token instanceof PreAuthenticatedToken && $token->getProviderKey() === $providerKey;
    }

    public function authenticateToken(TokenInterface $token, UserProviderInterface $userProvider, $providerKey)
    {
        if (!$userProvider instanceof ApiKeyUserProvider) {
            throw new \InvalidArgumentException(
                sprintf(
                    'The user provider must be an instance of ApiKeyUserProvider (%s was given).',
                    get_class($userProvider)
                )
            );
        }

        $apiKey = $token->getCredentials();
        $username = $userProvider->getUsernameForApiKey($apiKey);

        if (!$username) {
            // CAUTION: this message will be returned to the client
            // (so don't put any un-trusted messages / error strings here)
            throw new CustomUserMessageAuthenticationException(
                sprintf('API Key "%s" does not exist.', $apiKey)
            );
        }

        $user = $userProvider->loadUserByUsername($username);
        // dd($user);

        if (!$user || is_null($user)){
            if ($this->request->get('_route') === 'index_teacher'){
                $url = $this->router->generate('teacher_login');
                return new RedirectResponse($url);
            }elseif ($this->request->get('_route') === 'index_student'){
                $url = $this->router->generate('student_login');
                return new RedirectResponse($url);
            }
        }
        return new PreAuthenticatedToken(
            $user,
            $apiKey,
            $providerKey,
            $user->getRoles()
        );
    }

    /**
     * This is called when an interactive authentication attempt fails. This is
     * called by authentication listeners inheriting from
     * AbstractAuthenticationListener.
     *
     * @param Request $request
     * @param AuthenticationException $exception
     * @return Response The response to return, never null
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        if ($request->get('_route') === 'index_teacher'){
            $url = $this->router->generate('teacher_login');
            return new RedirectResponse($url);
        }elseif ($request->get('_route') === 'index_student'){
            $url = $this->router->generate('student_login');
            return new RedirectResponse($url);
        }
//        return new Response(
//            strtr($exception->getMessageKey(), $exception->getMessageData()),
//            401
//        );
    }
}