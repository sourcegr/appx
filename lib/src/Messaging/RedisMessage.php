<?php
    declare(strict_types=1);

    namespace Sourcegr\Framework\Messaging;


    use Redis;
    use Sourcegr\Framework\Base\RedisConnection;


    class RedisMessage implements MessagingInterface
    {
        protected Redis $connection;


        public function __construct(RedisConnection $redis)
        {
            $this->connection = $redis->getConnection();
        }


        public function sendMessage(string $channel, $message)
        {
            try {
                $this->connection->publish($channel, json_encode($message, JSON_UNESCAPED_UNICODE));
            } catch (\Exception $exception) {
                dd($exception);
            }
        }
    }

