<?php
    declare(strict_types=1);

    namespace Sourcegr\Framework\Database\QueryBuilder;


    class QBHelpers
    {
        public static function isAssocArray($arr) {
            return count(array_filter(array_keys($arr), 'is_string')) > 0;
        }
    }