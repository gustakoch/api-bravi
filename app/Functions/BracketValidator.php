<?php

namespace App\Functions;

class BracketValidator
{
    public static function isValid(string $str): bool
    {
        $stack = [];
        $brackets = ['(' => ')', '[' => ']', '{' => '}'];
        foreach (str_split($str) as $char) {
            if (in_array($char, array_keys($brackets))) {
                array_push($stack, $char);
            } elseif (in_array($char, array_values($brackets))) {
                if (empty($stack) || $brackets[array_pop($stack)] !== $char) {
                    return false;
                }
            }
        }

        return empty($stack);
    }
}
