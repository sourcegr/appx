<?php


    namespace App\Http\Auth\Providers;

    use App\Models\Contact;
    use Sourcegr\Framework\Database\QueryBuilder\DB;

    class FreakquentUserProvider
    {
        /**
         * @var DB $service
         */
        protected $service;
        protected $model;

        public function __construct($app, $conf)
        {
            // init the DB in case it is not inited...
            $app->getService('database');
            $this->model = $conf['MODEL'];
        }

        public function getUserFromHash($hash) {
            $m = $this->model;
            $a = new $m();
            return $m::find(1);
        }

        public function authenticate($params) {
            $m = $this->model;

            $users = $m::where(function($q) use ($params) {
                return $q->where($params);
            }, 'id');

            return $a[0] ?? null;

        }

    }