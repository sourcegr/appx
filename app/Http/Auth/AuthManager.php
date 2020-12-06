<?php

    declare(strict_types=1);

    namespace App\Http\Auth;


    class AuthManager
    {
        public $userProvider;
        public $driver;

        public function setDriver($driver)
        {
            $this->driver = $driver;
            return $this;
        }

        public function setUserProvider($userProvider)
        {
            $this->userProvider = $userProvider;
            return $this;
        }

        public function getLoggedInUser()
        {
            $hash = $this->driver->getUserHash();
            $result = $this->userProvider->getUserFromHash($hash);
            return $result;
        }
    }