<?php

    declare(strict_types=1);


    namespace App\ServiceProviders;


    use Sourcegr\Framework\Base\ServiceProvider;
    use Sourcegr\Framework\Filesystem\FileSystemManager;
    use Sourcegr\Framework\Filesystem\FileSystemManagerInterface;


    class FileSystemsProvider extends ServiceProvider
    {

        public function register()
        {
            $config = $this->loadConfig('filesystems');
            $manager = new FileSystemManager();

            foreach ($config as $driveName => $driveConfig) {
                $manager->createDrive($driveName, $driveConfig);

                // register if it is a named drive
                if ($driveConfig['name'] ?? null) {
                    $this->container->instance($driveConfig['name'], $manager->drive($driveName));
                }
            }

            $this->container->instance(FileSystemManagerInterface::class, $manager);
        }

        public function boot() {

        }
    }