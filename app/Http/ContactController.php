<?php

    declare(strict_types=1);


    namespace App\Http;


    use Sourcegr\Framework\App\ContainerInterface;
    use Sourcegr\Framework\Database\QueryBuilder\DB;
    use Sourcegr\Framework\Http\Session\SessionInterface;
    use Symfony\Component\Console\Helper\Table;

    class ContactController
    {
        public $session;

//        public function __construct(SessionInterface $session)
//        {
//            echo '<hr>OK<hr>';
//            $this->session = $session;
//        }

        public function run(ContainerInterface $container ) {
//            $a = $container->get('DB.connections.default');
//            $b = $container->get('DB.connections.default');
            echo '<div style="background:#c99;border:2px solid red"><b>This is inside the controller</b></div>';
            return [$this->session, $container];
        }

    }