<?php


    namespace App\Http\Auth\Drivers;


    use Sourcegr\Framework\Http\Session\SessionHandler;

    class SessionAuthDriver
    {
        /**
         * @var SessionHandler $engine
         */
        protected $session;
        const USER_HASH = 'appx_user_hash';


        public function __construct($app)
        {
            $this->session = $app->getService('session');
        }

        public function getUserHash() {
            return $this->session->get(static::USER_HASH);
        }
    }