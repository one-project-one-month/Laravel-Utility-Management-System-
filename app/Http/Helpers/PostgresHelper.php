<?php

namespace App\Http\Helpers;

trait PostgresHelper
{
    /**
     * Convert php array into postgresql array string
     *
     * @param array $array
     *
     * @return string
     */
    protected function nativeArrayToPgArrayString(array $array): string
    {
        return "{" . implode(",", $array) . "}";
    }

    /**
     * Convert Postgresql Array String into php array
     *
     * @param string $string postgresql array string
     *
     * @return array
     */
    protected function pgArrayStringToNativeArray(string $string): array
    {
        return array_map(
            // remove curly braces and surrounding quotes from each element
            fn($item) => trim($item, '"{}'),
            explode(',', $string)
        );
    }
}
