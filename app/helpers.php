<?php

    declare(strict_types=1);

    use Sourcegr\Framework\Base\Helpers\Str;

    function compileForValue($v)
    {
        if (is_callable($v)) {
            return $v();
        }
        return $v;
    }


    function env($var, $default = null)
    {

        $value = $_ENV[$var] ?? false;

        if ($value === false) {
            return compileForValue($default);
        }

        switch (strtolower($value)) {
            case 'true':
            case '(true)':
                return true;

            case 'false':
            case '(false)':
                return false;

            case 'empty':
            case '(empty)':
                return '';

            case 'null':
            case '(null)':
                return;
        }

        if (Str::startsWith($value, '"') && Str::endsWith($value, '"')) {
            return substr($value, 1, -1);
        }

        return $value;
    }
