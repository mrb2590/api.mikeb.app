<?php

namespace App\Traits;

trait OwnsObject
{
    /**
     * Check if a user owns and object.
     *
     * @param object $object
     */
    public function owns($object)
    {
        return $this->id === $object->owned_by_id;
    }

    /**
     * Check if a user does not own an object.
     *
     * @param object $object
     */
    public function doesNotOwn($object)
    {
        return ! $this->owns($object);
    }
}
