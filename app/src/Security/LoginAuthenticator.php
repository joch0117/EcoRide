<?php

namespace App\Security;


use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\RememberMeBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\SecurityRequestAttributes;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class LoginAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'app_login';

    public function __construct(private UrlGeneratorInterface $urlGenerator,private UserRepository $userRepository,private RequestStack $requestStack)
    {
    }

    public function authenticate(Request $request): Passport
    {
        $email = $request->request->get('email','');
        $password = $request->request->get('password','');
        $csrfToken = $request->request->get('_csrf_token','');
    
        

        if (empty($email) || empty($password)) {
            throw new CustomUserMessageAuthenticationException('Identifiant incorrects.');
        }

        $request->getSession()->set(SecurityRequestAttributes::LAST_USERNAME, $email);

        return new Passport(
            new UserBadge($email, function(string $userIdentifier) use ($password){
                        $user = $this->userRepository->findOneBy(['email' => $userIdentifier]);
                        
                        file_put_contents('/tmp/user-debug.log', "Tentative avec email : $userIdentifier\n", FILE_APPEND);

                        if (!$user) {
                        file_put_contents('/tmp/user-debug.log', "❌ Utilisateur NON trouvé : $userIdentifier\n", FILE_APPEND);
                        throw new CustomUserMessageAuthenticationException('Utilisateur introuvable.');
                        }
                        
                        if (!$user) {
                            throw new CustomUserMessageAuthenticationException('Utilisateur introuvable.');
                        }

                        if ($user->isSuspended()) {
                            throw new CustomUserMessageAuthenticationException('Votre compte a été suspendu.');
                        }

                            if (!password_verify($password, $user->getPassword())) {
                            file_put_contents('/tmp/password-check.log', "❌ ECHEC password_verify pour {$user->getEmail()}\n", FILE_APPEND);
                            throw new CustomUserMessageAuthenticationException('Mot de passe incorrect');
                            }

                            file_put_contents('/tmp/password-check.log', "✅ SUCCÈS password_verify pour {$user->getEmail()}\n", FILE_APPEND);


                    return $user;
                }),
            new PasswordCredentials($password),
            [
                new CsrfTokenBadge('authenticate', $csrfToken),
                new RememberMeBadge(),
            ]
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {

        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        }

        return new RedirectResponse($this->urlGenerator->generate('app_home'));
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
}
