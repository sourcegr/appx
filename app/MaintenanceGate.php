<?php

    declare(strict_types=1);


    namespace App;


    use Sourcegr\Framework\App\AppInterface;
    use Sourcegr\Framework\Http\Boom;
    use Sourcegr\Framework\Http\BoomException;
    use Sourcegr\Framework\Http\Request\RequestInterface;
    use Sourcegr\Framework\Http\Response\HTTPResponseCode;

    class MaintenanceGate
    {
        protected $app;
        protected $request;
        protected $user = null;

        public function __construct(AppInterface $app, RequestInterface $request)
        {
            $this->app = $app;
            $this->request = $request;
        }

        public function allows(array $urlRegexps) {
            foreach ($urlRegexps as $regexp){
                if (preg_match_all($regexp, $this->request->url) > 0) {
                    return true;
                }
            }

            return false;
        }

        public function sendMaintainanceSignal() {
            throw new BoomException(new Boom(HTTPResponseCode::HTTP_SERVICE_UNAVAILABLE));
        }
    }