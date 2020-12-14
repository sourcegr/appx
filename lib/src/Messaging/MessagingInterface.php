<?php
    declare(strict_types=1);

    namespace Sourcegr\Framework\Messaging;


    interface MessagingInterface
    {
        public function sendMessage(string $channel, $message);
    }