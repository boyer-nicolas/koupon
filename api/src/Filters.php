<?php

namespace Koupon\Api;

final class Filters
{
    public static function validateString(string $input): string
    {
        $input = htmlspecialchars($input);
        $input = stripslashes($input);
        $input = trim($input);
        $input = filter_var($input, FILTER_UNSAFE_RAW);
        return $input;
    }

    public static function validateEmail(string $email): bool
    {
        $email = htmlspecialchars($email);
        $email = stripslashes($email);
        $email = trim($email);
        $email = filter_var($email, FILTER_UNSAFE_RAW);
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    public static function validateUrl(string $url): bool
    {
        $url = htmlspecialchars($url);
        $url = stripslashes($url);
        $url = trim($url);
        $url = filter_var($url, FILTER_UNSAFE_RAW);
        return filter_var($url, FILTER_VALIDATE_URL);
    }

    public static function validateInt(string $int): int
    {
        $int = str_replace(" ", "", $int);
        $int = htmlspecialchars($int);
        $int = filter_var($int, FILTER_UNSAFE_RAW);
        $int = filter_var($int, FILTER_VALIDATE_INT);
        return $int;
    }

    public static function validateFloat(string $float): float
    {
        $float = htmlspecialchars($float);
        $float = stripslashes($float);
        $float = trim($float);
        $float = filter_var($float, FILTER_UNSAFE_RAW);
        $float = filter_var($float, FILTER_VALIDATE_FLOAT);
        return $float;
    }

    public static function validateArray(array $array): array
    {
        $array = filter_var_array($array, FILTER_UNSAFE_RAW);
        return $array;
    }
}
