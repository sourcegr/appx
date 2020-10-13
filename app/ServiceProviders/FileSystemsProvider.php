<?php

    declare(strict_types=1);


    namespace App\ServiceProviders;


    class FileSystemsProvider extends ServiceProvider
    {
        protected $manager;

        public function init()
        {
            return $this;
        }

        public function getService() {
            if ($this->manager) {
                return $this->manager;
            }

            $config = $this->app->loadConfig('filesystems');

            $this->manager = new $config['manager']();

            $drives = $config['drives'];

            foreach ($drives as $driveName => $driveConfig) {
                $drive = new $driveConfig['engine']($driveConfig['path']);
                $this->manager->attachDrive($driveName, $drive);
            }

            return $this->manager;
        }
    }