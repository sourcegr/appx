<?php

    declare(strict_types=1);


    use App\Models\Contact;
    use Sourcegr\Framework\Filesystem\FileSystemManager;

    require '../vendor/autoload.php';

    $app = new App\App();
    $app->bootstrap();

    $app->kickStart(
        $app->getService('request')
    );

    /** @var FileSystemManager $storage */
    $storage = $app->getService('fileEngine');
    die();










//    $req->
//    $session->addFlash('message', 'error');
//    var_dump($_SESSION);

    $app->tearDown();
    var_dump($req);


    //    $c = Contact::all();
    //    var_dump($c);

    //
    //    $request = new Sourcegr\Http\Request\Request();
    //
    //    $response = $app->init($request);


