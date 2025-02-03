<?php

namespace RedberryProducts\LaravelBogPayment\Traits\Utils;

trait Arrayable
{
    public function offsetExists($offset): bool
    {
        return isset($this->$offset);
    }

    public function offsetGet($offset): mixed
    {
        return $this->$offset;
    }

    public function offsetSet($offset, $value): void
    {
        $this->$offset = $value;
    }

    public function offsetUnset($offset): void
    {
        unset($this->$offset);
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }

}
