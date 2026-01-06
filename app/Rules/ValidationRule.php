<?php

namespace App\Rules;

use Closure;

class ValidationRule
{
    public static function email(): Closure
    {
        return function (string $attribute, mixed $value, Closure $fail): void {
            if (!preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $value)) {
                $fail(':attribute must be a valid email address.');
            }
        };
    }

    public static function phone(): Closure
    {
        return function (string $attribute, mixed $value, Closure $fail): void {
            if (!preg_match('/^\(\d{3}\) \d{3}-\d{4}$/', $value)) {
                $fail(':attribute must be a valid phone number.');
            }
        };
    }

    public static function image(int $maxSize): Closure
    {
        return function (string $attribute, mixed $value, Closure $fail) use ($maxSize): void {
            if (!$value->isValid() || !$value->isFile() || !$value->isReadable()) {
                $fail(":attribute must be a valid image file.");
                return;
            }

            $mimeType = $value->getMimeType();
            $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif'];

            if (!in_array($mimeType, $allowedMimeTypes, true)) {
                $fail(":attribute must be an image of type: jpg, jpeg, png, gif.");
                return;
            }

            if ($value->getSize() > $maxSize * 1024) { // Convert KB to bytes
                $fail(":attribute must not exceed {$maxSize} KB.");
            }
        };
    }

    public static function excel(int $maxSize): Closure
    {
        return function (string $attribute, mixed $value, Closure $fail) use ($maxSize): void {
            if (!$value->isValid() || !$value->isFile() || !$value->isReadable()) {
                $fail(":attribute must be a valid file.");
                return;
            }

            $mimeType = $value->getMimeType();
            $allowedMimeTypes = [
                'application/vnd.ms-excel', // .xls
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' // .xlsx
            ];

            if (!in_array($mimeType, $allowedMimeTypes, true)) {
                $fail(":attribute must be an Excel file of type: xls, xlsx.");
                return;
            }

            if ($value->getSize() > $maxSize * 1024) {
                $fail(":attribute must not exceed {$maxSize} KB.");
            }
        };
    }
}

