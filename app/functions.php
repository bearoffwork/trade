<?php

if (!function_exists('bcceil')) {
    function bcceil(string $num): string
    {
        if (str_contains($num, '.')) {
            [$integer, $fraction] = explode('.', $num, 2);

            return bccomp($fraction, '0', strlen($fraction)) === 1
                ? bcadd($integer, '1', 0)
                : $integer;
        }

        return $num;
    }
}

if (!function_exists('bcfloor')) {
    function bcfloor(string $num): string
    {
        if (str_contains($num, '.')) {
            [$integer, $fraction] = explode('.', $num, 2);

            return $integer.'.'.str_repeat('0', strlen($fraction));
        }

        return $num;
    }
}
