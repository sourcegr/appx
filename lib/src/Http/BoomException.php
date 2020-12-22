<?php

    declare(strict_types=1);

    namespace Sourcegr\Framework\Http;

    use Sourcegr\Framework\Http\Response\HTTPResponseCode;
    use Throwable;

    class BoomException extends \Exception
    {
        public $boom;

        public function __construct(Boom $boom, $message = "", $code = 0, Throwable $previous = null)
        {
            $this->boom = $boom;
            parent::__construct($message, $code, $previous);
        }

        public static function Http(int $HTTPErrorCode = 404, string $message=null, $payload = null) {
            if ($message === null) {
                $message = HTTPResponseCode::$statusTexts[$HTTPErrorCode];
            }
            return new static(new Boom($HTTPErrorCode, $message, $payload));
        }
    }