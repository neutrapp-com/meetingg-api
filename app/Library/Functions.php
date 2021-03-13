<?php

namespace Meetingg\Library;

class Functions
{

    /**
     * Indexed Array
     *
     * @param array $arr
     * @return void
     */
    public static function indexedArray(array &$arr)
    {
        return $arr === array_values($arr);
    }
}
