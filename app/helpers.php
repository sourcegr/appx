<?php

    declare(strict_types=1);

    function env(string $var, $default = null)
    {
        return $_ENV[$var] ?? $default;
    }
