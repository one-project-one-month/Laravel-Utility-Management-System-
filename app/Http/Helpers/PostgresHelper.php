<?php

namespace App\Http\Helpers;

trait PostgresHelper
{
    /**
     * Convert php string into postgresql array string
     *
     * @param string $string
     *
     * @return string
     */
    protected function nativeStringToPgArrayString(string $string): string
    {
        $textArray = array_map('trim', explode(',', $string));

        // change to text array format
        return "{" . implode(",", $textArray) . "}";
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
