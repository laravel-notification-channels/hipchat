<?php

if (! function_exists('str_empty')) {
    /**
     * Returns true if a string is empty or null.
     *
     * @param string|null $value
     * @param bool $trim
     * @return bool
     */
    function str_empty($value, $trim = false)
    {
        if ($value === null) {
            return true;
        }

        if ($trim) {
            return trim((string) $value) === '';
        }

        return (string) $value === '';
    }
}

if (! function_exists('str_array_filter')) {
    /**
     * Filter out null and empty string values.
     *
     * @param $array
     * @return array
     */
    function str_array_filter($array)
    {
        return array_filter($array, function ($value) {
            if (is_array($value)) {
                return ! empty($value);
            }

            return ! str_empty($value);
        });
    }
}
