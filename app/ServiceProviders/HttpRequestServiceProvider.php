<?php

    declare(strict_types=1);


    namespace App\ServiceProviders;

    use Sourcegr\Framework\Http\HttpRequest;

    class HttpRequestServiceProvider extends ServiceProvider
    {
        public function init()
        {
            return HttpRequest::fromHTTP();
        }
    }