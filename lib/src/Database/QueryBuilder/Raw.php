<?php
    declare(strict_types=1);

    namespace Sourcegr\Framework\Database\QueryBuilder;


    class Raw
    {
        private $value;

        public function __construct($value)
        {
            $this->value = $value;
        }


        public static function now()
        {
            return new static('CURRENT_TIMESTAMP');
        }

        public static function from($rawString)
        {
            return new static($rawString);
        }



        public function getValue()
        {
            return $this->value;
        }
    }