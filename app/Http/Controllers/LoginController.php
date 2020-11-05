<?php

    declare(strict_types=1);


    namespace App\Http\Controllers;


    use Sourcegr\Framework\Base\View\ViewManager;
    use Sourcegr\Framework\Http\Redirect\Redirect;
    use Sourcegr\Framework\Http\Request\RequestInterface;
    use Sourcegr\Framework\Http\Session\SessionInterface;

    class LoginController
    {
        public function authenticate(RequestInterface $request, Redirect $redirect)
        {
            $email = $request->get('email');
            $password = $request->get('password');


            if($request->auth->authenticate([
                'email' => $email,
                'password' => $password,
            ])){
                return $redirect->to('/app');
            } else {
                return $redirect->to('/login')->with('error', 'invalid email or password');
            }
        }

        public function login(ViewManager $viewManager) {
            return $viewManager->make('login');
        }

        public function logout(RequestInterface $request, Redirect $redirect) {
            $request->auth->logout();
            return $redirect->to('/app');
        }

    }