<?php

    declare(strict_types=1);


    namespace App\Http\Middleware;


    use Sourcegr\Framework\Base\Encryptor\EncryptorInterface;
    use Sourcegr\Framework\Http\Boom;
    use Sourcegr\Framework\Http\Middleware\BaseMiddleware;
    use Sourcegr\Framework\Http\Request\RequestInterface;
    use Sourcegr\Framework\Http\Response\HTTPResponseCode;
    use Sourcegr\Framework\Http\Response\ResponseInterface;

    class VerifyCSRFMiddleware extends BaseMiddleware
    {
        protected $skipRegExps = [];

        protected $encryptor;
        protected $request;

        public function handle(EncryptorInterface $encryptor, RequestInterface $request, ResponseInterface $response)
        {
            $this->encryptor = $encryptor;
            $this->request = $request;

            if ($this->inSkipRegExps($this->request->url)) {
                return $response;
            }

            // actual checks...
            // get the correct token from the session
            $serverToken = $this->request->session->getCSRF();

            $xcsrf = $this->request->getHeader('X-CSRF-TOKEN');
            if ($xcsrf) {
                $token = $this->encryptor->decrypt($xcsrf);
                if ($token === $serverToken) {
                    return $response;
                }
            }

            $token = $this->request->getHeader('C-CSRF-TOKEN');

            if ($token === $serverToken) {
                return $response;
            }

            // check also in the POST variables
            $tokenName = $this->request->session->getCSRFFieldName();
            $token = $this->request->get($tokenName);

            if ($token === $serverToken) {
                return $response;
            }


            return new Boom(HTTPResponseCode::HTTP_FORBIDDEN, 'CSRF/XSRF paramter missing', true);
        }


        protected function shouldSkipMethod($method)
        {
            return in_array($method, ['HEAD', 'GsET', 'OPTIONS']);
        }


        protected function inSkipRegExps($request)
        {
            return in_array($this->request->method, ['HEAD', 'GET', 'OPTIONS']);
        }


        public function getToken($request)
        {
            $token = $request->post->get('_token') ?: $request->headers->get('X-CSRF-TOKEN');

            if (!$token && $encryptedToken = $request->headers->get('X-CSRF-TOKEN')) {
                $token = $this->encryptor->decrypt($encryptedToken);
            }

            // todo also check for unencrypted

            return $token;
        }

        protected function verifyToken($token)
        {

        }
    }

    //$this->app->registerShutdownCallback(function () {
    //send cookies if they should be sent
    //$response->headers->set();
    //echo "TODO FIX VerifyCSRFMiddleware ". __FILE__ .'<hr>';
    //});
