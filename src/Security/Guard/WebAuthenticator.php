<?php

declare(strict_types=1);

namespace App\Security\Guard;

use App\DataTransferObject\Credentials;
use App\Form\LoginType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;

class WebAuthenticator extends AbstractFormLoginAuthenticator
{
    private UrlGeneratorInterface $urlGenerator;
    private FormFactoryInterface $formFactory;
    private UserPasswordEncoderInterface $userPasswordEncoder;

    public function __construct(
        UrlGeneratorInterface $urlGenerator,
        FormFactoryInterface $formFactory,
        UserPasswordEncoderInterface $userPasswordEncoder
    ) {
        $this->urlGenerator = $urlGenerator;
        $this->formFactory = $formFactory;
        $this->userPasswordEncoder = $userPasswordEncoder;
    }

    protected function getLoginUrl()
    {
        return $this->urlGenerator->generate('security_login');
    }

    public function supports(Request $request)
    {
        return $request->isMethod(Request::METHOD_POST)
            && 'security_login' === $request->attributes->get('_route');
    }

    public function getCredentials(Request $request)
    {
        $credentials = new Credentials();
        $form = $this->formFactory->create(LoginType::class, $credentials)->handleRequest($request);

        if (!$form->isValid()) {
            return;
        }

        return $credentials;
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        return $userProvider->loadUserByUsername($credentials->getUsername());
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        if ($this->userPasswordEncoder->isPasswordValid($user, $credentials->getPassword())) {
            return true;
        }

        throw new AuthenticationException('Password not valid.');
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $providerKey)
    {
        return new RedirectResponse($this->urlGenerator->generate('index'));
    }
}
