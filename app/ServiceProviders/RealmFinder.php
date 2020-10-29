<?php

    declare(strict_types=1);


    namespace App\ServiceProviders;


    use Sourcegr\Framework\Http\Request\RequestInterface;

    class RealmFinder
    {
        public const DEFAULT_REALM = 'WEB';
        public const DEFAULT_API_VERSION = 'API';

        public static function fromRequest(RequestInterface $request)
        {
            if ($request->expectsJson()) {
                return static::DEFAULT_API_VERSION;
//                if ($request->URLStartsWith('api/1')) {
//                    return 'API1';
//                }
//                if ($request->URLStartsWith('api/2')) {
//                    return 'API2';
//                }
            }
            return static::DEFAULT_REALM;
        }
    }