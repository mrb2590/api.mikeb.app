<?php

namespace App\Traits;

trait OwnsObject
{
    /**
     * Check if a user owns and object.
     *
     * @param object $object
     */
    protected function owns($object)
    {
        return $this->id === $object->owned_by;
    }

    /**
     * Check if a user does not own an object.
     *
     * @param object $object
     */
    protected function doesNotOwn($object)
    {
        return ! $this->owns($object);
    }
}