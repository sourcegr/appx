<?php

    declare(strict_types=1);


    namespace App\ServiceProviders;



    use Sourcegr\Framework\Base\ServiceProvider;
    use Sourcegr\Framework\Messaging\MessagingInterface;
    use Sourcegr\Framework\Messaging\RedisMessage;


    class MessagingServiceProvider extends ServiceProvider
    {
        public function register()
        {
            $this->container->bind(MessagingInterface::class, RedisMessage::class);
        }

        public function boot() {

        }
    }