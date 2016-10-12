<?php

namespace Acme\TrainingBundle\Controller;

use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HelloController extends Controller
{
    public function helloAction(Request $request)
    {
        return ['name' => $request->query->get('name')];
    }

    public function rpcAction($name)
    {
        return "Hello {$name}";
    }

    public function tokenAuthenticationAction(Request $request)
    {
        $username = $request->request->get('username');
        $password = $request->request->get('password');

        $user = new User($username, $password, ['ROLE_USER']);

        if (!$user || !$this->get('security.password_encoder')->isPasswordValid($user, $password)) {
            throw new AccessDeniedHttpException();
        }

        $token = $this->get('lexik_jwt_authentication.encoder')
            ->encode(['username' => $user->getUsername(), 'roles' => $user->getRoles()]);

        return ['token' => $token];
    }

    public function secureAction()
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        return ['test' => 'tesT', 'username' => $user->getUsername()];
    }
}
