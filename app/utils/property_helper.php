<?php 

if (!function_exists('is_value_exists')) {
    function is_value_exists($value): bool
    {
        return !is_null($value) && $value != '';
    }
}