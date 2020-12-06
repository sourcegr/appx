<?php

    declare(strict_types=1);


    namespace App\Http\Middleware;


    use App\MaintenanceGate;
    use Sourcegr\Framework\App\AppInterface;
    use Sourcegr\Framework\Http\Boom;
    use Sourcegr\Framework\Http\Middleware\BaseMiddleware;
    use Sourcegr\Framework\Http\Redirect\TemporaryRedirect;
    use Sourcegr\Framework\Http\Response\HTTPResponseCode;
    use Sourcegr\Framework\Http\Response\ResponseInterface;


    class CheckMaintenanceModeMiddleware extends BaseMiddleware
    {
        protected $redirectTo;
        protected $allowedUrls = [];

        public function handle(AppInterface $app, MaintenanceGate $gate, ResponseInterface $response)
        {
            if ($gate->allows($this->allowedUrls)) {
                return $response;
            }


            if (!$app->isDownForMaintenance()) {
                return $response;
            }

            // if redirectTo is set, send a Temporary Redirect
            if ($this->redirectTo) {
                return new TemporaryRedirect($this->redirectTo, true);
            }

            return new Boom(HTTPResponseCode::HTTP_SERVICE_UNAVAILABLE);
        }
    }
