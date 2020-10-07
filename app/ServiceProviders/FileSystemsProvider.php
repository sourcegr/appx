<?php

    declare(strict_types=1);


    namespace App\ServiceProviders;


    use Sourcegr\Framework\Filesystem\FileSystemManager;

    class FileSystemsProvider extends ServiceProvider
    {
        protected $manager;
        public function init(): FileSystemManager
        {
            $drives = $this->app->loadConfig('filesystems');

            $this->manager = new FileSystemManager();

            foreach ($drives as $driveName => $driveConfig) {
                $this->manager->createDrive($driveName, $driveConfig);
            }

            return $this->manager;
        }
    }