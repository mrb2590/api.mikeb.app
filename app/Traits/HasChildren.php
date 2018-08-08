<?php

namespace App\Traits;

trait HasChildren
{
    /**
     * Recursively loop through all child folders.
     *
     * @param object $object
     * @param function $callback
     */
    public static function loopAllChildren($object, $callback)
    {
        $object->all_children()->chunk(500, function($children) use ($object, $callback) {
            foreach ($children as $child) {
                $callback($child);

                self::loopAllChildren($child, $callback);
            }
        });
    }
}
