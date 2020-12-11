<?php

    declare(strict_types=1);


    namespace Sourcegr\Framework\App\Container;

    use Closure;

    interface ContextualBindingBuilderInterface
    {
        /**
         * Define the abstract target that depends on the context.
         *
         * @param string $abstract
         *
         * @return $this
         */
        public function needs($abstract);

        /**
         * Define the implementation for the contextual binding.
         *
         * @param Closure|string $implementation
         *
         * @return void
         */
        public function give($implementation);

        /**
         * Define tagged services to be used as the implementation for the contextual binding.
         *
         * @param string $tag
         *
         * @return void
         */
        public function giveTagged($tag);
    }